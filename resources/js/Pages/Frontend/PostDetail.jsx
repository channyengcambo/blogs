import React, { useState, useEffect } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import FrontendLayout from '@/Layouts/FrontendLayout';
import axios from 'axios';

export default function PostDetail({ post, relatedPosts }) {
    const [scrollProgress, setScrollProgress] = useState(0);
    const [likes, setLikes] = useState(post.likes_count || 0);
    const [hasLiked, setHasLiked] = useState(false);
    const [copied, setCopied] = useState(false);

    // Reading progress calculation
    useEffect(() => {
        const handleScroll = () => {
            const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
            if (totalHeight > 0) {
                const currentProgress = (window.scrollY / totalHeight) * 100;
                setScrollProgress(Math.min(100, Math.max(0, currentProgress)));
            }
        };

        window.addEventListener('scroll', handleScroll, { passive: true });
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    // Handle like interaction (optimistic UI)
    const handleLike = async () => {
        if (hasLiked) return;
        setLikes((prev) => prev + 1);
        setHasLiked(true);

        try {
            await axios.post(`/posts/${post.id}/like`);
        } catch (error) {
            console.error('Failed to register like', error);
        }
    };

    // Copy article link to clipboard
    const handleCopyLink = () => {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.href);
            setCopied(true);
            setTimeout(() => setCopied(false), 2500);
        }
    };

    const shareTwitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(post.title)}&url=${encodeURIComponent(typeof window !== 'undefined' ? window.location.href : '')}`;
    const shareLinkedInUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(typeof window !== 'undefined' ? window.location.href : '')}`;

    return (
        <FrontendLayout
            title={post.title}
            description={post.sub_title || post.body?.substring(0, 160)}
            image={post.featured_image}
        >
            {/* Top Reading Progress Bar */}
            <div
                className="fixed top-0 left-0 h-1 bg-gradient-to-r from-sky-400 via-indigo-500 to-purple-500 z-50 transition-all duration-75"
                style={{ width: `${scrollProgress}%` }}
            />

            <article className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
                
                {/* Breadcrumbs */}
                <nav className="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6">
                    <Link href="/" className="hover:text-sky-500 transition">Home</Link>
                    <span>/</span>
                    {post.category && (
                        <>
                            <Link href={`/?category=${post.category.slug}`} className="hover:text-sky-500 transition">
                                {post.category.name}
                            </Link>
                            <span>/</span>
                        </>
                    )}
                    <span className="text-slate-400 dark:text-slate-500 truncate max-w-xs">{post.title}</span>
                </nav>

                {/* Article Header Meta */}
                <div className="space-y-4">
                    <div className="flex flex-wrap items-center gap-2.5">
                        {post.category && (
                            <Link
                                href={`/?category=${post.category.slug}`}
                                className="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-sky-500 text-white shadow-md shadow-sky-500/20 hover:bg-sky-600 transition"
                            >
                                {post.category.name}
                            </Link>
                        )}
                        <span className="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            <svg className="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {post.published_at ? new Date(post.published_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'Recently'}
                        </span>
                        <span className="text-slate-300 dark:text-slate-700">•</span>
                        <span className="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            <svg className="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {post.reading_time || 4} min read
                        </span>
                    </div>

                    {/* Article Title */}
                    <h1 className="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {post.title}
                    </h1>

                    {/* Sub-title / Excerpt */}
                    {post.sub_title && (
                        <p className="text-lg sm:text-xl text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            {post.sub_title}
                        </p>
                    )}

                    {/* Author Bar & Action Buttons */}
                    <div className="pt-4 pb-6 border-b border-slate-200/80 dark:border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div className="flex items-center gap-3">
                            <div className="w-11 h-11 rounded-full bg-gradient-to-tr from-sky-400 via-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                {(post.author_name || 'A').charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h4 className="text-sm font-bold text-slate-900 dark:text-white">
                                    {post.author_name || 'Admin'}
                                </h4>
                                <p className="text-xs text-slate-500 dark:text-slate-400">
                                    Engineering & Systems Author
                                </p>
                            </div>
                        </div>

                        {/* Social & Sharing Pills */}
                        <div className="flex items-center gap-2">
                            <button
                                type="button"
                                onClick={handleLike}
                                className={`inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold transition shadow-sm ${
                                    hasLiked
                                        ? 'bg-pink-500 text-white shadow-pink-500/30'
                                        : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10 hover:border-pink-500 hover:text-pink-500'
                                }`}
                                title="Applaud / Like Article"
                            >
                                <svg className={`w-4 h-4 ${hasLiked ? 'scale-110' : ''} transition-transform`} fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clipRule="evenodd" />
                                </svg>
                                <span>{likes}</span>
                            </button>

                            <button
                                type="button"
                                onClick={handleCopyLink}
                                className="p-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 transition"
                                title="Copy Article Link"
                            >
                                {copied ? (
                                    <span className="text-xs text-emerald-500 font-bold px-1">Copied!</span>
                                ) : (
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                )}
                            </button>

                            <a
                                href={shareTwitterUrl}
                                target="_blank"
                                rel="noreferrer"
                                className="p-2 rounded-xl text-slate-500 hover:text-sky-500 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 transition"
                                title="Share on Twitter / X"
                            >
                                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                            </a>

                            <a
                                href={shareLinkedInUrl}
                                target="_blank"
                                rel="noreferrer"
                                className="p-2 rounded-xl text-slate-500 hover:text-sky-600 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 transition"
                                title="Share on LinkedIn"
                            >
                                <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {/* Hero Featured Image */}
                {post.featured_image && (
                    <div className="mt-8 rounded-3xl overflow-hidden bg-slate-900 border border-slate-200/80 dark:border-white/10 shadow-2xl">
                        <img
                            src={post.featured_image}
                            alt={post.title}
                            className="w-full aspect-[21/9] sm:aspect-[16/8] object-cover"
                        />
                    </div>
                )}

                {/* Article Body Content */}
                <div className="mt-12 text-slate-800 dark:text-slate-200 text-base sm:text-lg leading-relaxed space-y-6">
                    {post.body ? (
                        post.body.split('\n\n').map((paragraph, index) => {
                            if (!paragraph.trim()) return null;
                            
                            // Check if subheading
                            if (paragraph.startsWith('### ')) {
                                return (
                                    <h3 key={index} className="text-xl sm:text-2xl font-black text-slate-900 dark:text-white pt-6 pb-2 tracking-tight">
                                        {paragraph.replace('### ', '')}
                                    </h3>
                                );
                            }
                            if (paragraph.startsWith('## ')) {
                                return (
                                    <h2 key={index} className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white pt-8 pb-3 tracking-tight border-b border-slate-100 dark:border-white/[0.08]">
                                        {paragraph.replace('## ', '')}
                                    </h2>
                                );
                            }
                            // Blockquote
                            if (paragraph.startsWith('> ')) {
                                return (
                                    <blockquote key={index} className="pl-5 border-l-4 border-sky-500 italic my-6 text-slate-700 dark:text-slate-300 font-medium">
                                        {paragraph.replace('> ', '')}
                                    </blockquote>
                                );
                            }

                            return (
                                <p key={index} className="leading-8 text-slate-700 dark:text-slate-300">
                                    {paragraph}
                                </p>
                            );
                        })
                    ) : (
                        <p className="italic text-slate-400">No content available for this article.</p>
                    )}
                </div>

                {/* Article Tags Footer */}
                {post.tags && post.tags.length > 0 && (
                    <div className="mt-12 pt-6 border-t border-slate-200/80 dark:border-white/10">
                        <div className="flex flex-wrap items-center gap-2">
                            <span className="text-xs font-bold uppercase text-slate-400 mr-2">Tags:</span>
                            {post.tags.map((tag) => (
                                <Link
                                    key={tag.id}
                                    href={`/?tag=${tag.slug}`}
                                    className="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-sky-500 hover:text-white transition"
                                >
                                    #{tag.name}
                                </Link>
                            ))}
                        </div>
                    </div>
                )}

                {/* Post Footer Reaction Bar */}
                <div className="mt-10 p-6 rounded-3xl bg-slate-100/80 dark:bg-slate-900/60 border border-slate-200/80 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div className="flex items-center gap-3">
                        <button
                            type="button"
                            onClick={handleLike}
                            className={`p-3 rounded-2xl flex items-center gap-2 text-sm font-bold transition shadow-lg ${
                                hasLiked
                                    ? 'bg-pink-500 text-white shadow-pink-500/30'
                                    : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:border-pink-500 hover:text-pink-500'
                            }`}
                        >
                            <svg className="w-5 h-5 text-pink-500 fill-current" viewBox="0 0 20 20">
                                <path fillRule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clipRule="evenodd" />
                            </svg>
                            <span>{likes} Claps & Likes</span>
                        </button>
                        <span className="text-xs text-slate-500">Liked this deep dive? Show your appreciation!</span>
                    </div>

                    <Link
                        href="/"
                        className="text-xs font-bold text-sky-500 hover:text-sky-600 flex items-center gap-1"
                    >
                        <span>← Back to all articles</span>
                    </Link>
                </div>

                {/* Related Articles Section */}
                {relatedPosts && relatedPosts.length > 0 && (
                    <div className="mt-16 pt-12 border-t border-slate-200/80 dark:border-white/10">
                        <div className="flex items-center justify-between mb-8">
                            <h2 className="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                Related Deep Dives
                            </h2>
                            <Link href="/" className="text-xs font-bold text-sky-500 hover:underline">
                                View all →
                            </Link>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {relatedPosts.map((rel) => (
                                <article
                                    key={rel.id}
                                    className="group rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col"
                                >
                                    <div className="relative aspect-video w-full bg-slate-800 overflow-hidden">
                                        <img
                                            src={rel.featured_image || 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80'}
                                            alt={rel.title}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        />
                                    </div>
                                    <div className="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <span className="text-[10px] font-bold uppercase tracking-wider text-sky-500">
                                                {rel.category_name || 'Engineering'}
                                            </span>
                                            <Link href={`/posts/${rel.slug}`}>
                                                <h4 className="text-sm font-bold text-slate-900 dark:text-white group-hover:text-sky-500 transition line-clamp-2 mt-1">
                                                    {rel.title}
                                                </h4>
                                            </Link>
                                        </div>
                                        <div className="mt-4 pt-2 border-t border-slate-100 dark:border-white/[0.08] flex items-center justify-between text-[11px] text-slate-400">
                                            <span>{rel.reading_time || 3} min read</span>
                                            <span>{rel.views_count?.toLocaleString()} views</span>
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                    </div>
                )}

            </article>
        </FrontendLayout>
    );
}
