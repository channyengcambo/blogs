import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import FrontendLayout from '@/Layouts/FrontendLayout';

export default function Dashboard({ posts, metrics, filters }) {
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [deletingPostId, setDeletingPostId] = useState(null);

    const handleSearchSubmit = (e) => {
        e.preventDefault();
        router.get('/author/dashboard', {
            ...filters,
            search: searchQuery,
            page: 1,
        }, { preserveState: true, preserveScroll: true });
    };

    const handleStatusFilter = (status) => {
        router.get('/author/dashboard', {
            ...filters,
            status: filters.status === status ? '' : status,
            page: 1,
        }, { preserveState: true, preserveScroll: true });
    };

    const confirmDelete = (post) => {
        if (confirm(`Are you sure you want to delete "${post.title}"? This cannot be undone.`)) {
            setDeletingPostId(post.id);
            router.delete(`/author/posts/${post.id}`, {
                preserveScroll: true,
                onFinish: () => setDeletingPostId(null),
            });
        }
    };

    return (
        <FrontendLayout
            title="Author Studio & Dashboard"
            description="Manage your published articles, monitor readership metrics, and draft new software engineering stories."
        >
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                
                {/* Header Strip with Greeting and Write Button */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-8 border-b border-slate-200/80 dark:border-white/10">
                    <div>
                        <div className="flex items-center gap-2 mb-1">
                            <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span className="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">
                                Author Studio
                            </span>
                        </div>
                        <h1 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            My Articles & Stories
                        </h1>
                        <p className="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                            Write, edit, and track the impact of your published software engineering insights.
                        </p>
                    </div>

                    <div className="flex items-center gap-3">
                        <Link
                            href="/author/posts/create"
                            className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-sky-500 to-indigo-600 text-white hover:from-sky-600 hover:to-indigo-700 transition shadow-lg shadow-sky-500/25 hover:scale-105 duration-200"
                        >
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Write New Article</span>
                        </Link>
                    </div>
                </div>

                {/* KPI Metrics Cards */}
                <div className="grid grid-cols-2 md:grid-cols-5 gap-4 my-8">
                    <div className="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <p className="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Articles</p>
                        <p className="text-2xl font-black text-slate-900 dark:text-white mt-1">{metrics.total_posts}</p>
                    </div>
                    <div className="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <p className="text-[11px] font-semibold text-emerald-500 uppercase tracking-wider">Published</p>
                        <p className="text-2xl font-black text-slate-900 dark:text-white mt-1">{metrics.published_posts}</p>
                    </div>
                    <div className="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <p className="text-[11px] font-semibold text-amber-500 uppercase tracking-wider">Drafts</p>
                        <p className="text-2xl font-black text-slate-900 dark:text-white mt-1">{metrics.draft_posts}</p>
                    </div>
                    <div className="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <p className="text-[11px] font-semibold text-sky-500 uppercase tracking-wider">Total Reads</p>
                        <p className="text-2xl font-black text-slate-900 dark:text-white mt-1">{metrics.total_views.toLocaleString()}</p>
                    </div>
                    <div className="p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm col-span-2 md:col-span-1">
                        <p className="text-[11px] font-semibold text-pink-500 uppercase tracking-wider">Likes Received</p>
                        <p className="text-2xl font-black text-slate-900 dark:text-white mt-1">{metrics.total_likes.toLocaleString()}</p>
                    </div>
                </div>

                {/* Filter and Search Bar */}
                <div className="p-4 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-white/10 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                    <div className="flex items-center gap-2 w-full md:w-auto">
                        <button
                            onClick={() => handleStatusFilter('')}
                            className={`px-3.5 py-1.5 rounded-xl text-xs font-bold transition ${
                                !filters.status
                                    ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900'
                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                            }`}
                        >
                            All ({metrics.total_posts})
                        </button>
                        <button
                            onClick={() => handleStatusFilter('published')}
                            className={`px-3.5 py-1.5 rounded-xl text-xs font-bold transition ${
                                filters.status === 'published'
                                    ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20'
                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                            }`}
                        >
                            Published ({metrics.published_posts})
                        </button>
                        <button
                            onClick={() => handleStatusFilter('draft')}
                            className={`px-3.5 py-1.5 rounded-xl text-xs font-bold transition ${
                                filters.status === 'draft'
                                    ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20'
                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200'
                            }`}
                        >
                            Drafts ({metrics.draft_posts})
                        </button>
                    </div>

                    <form onSubmit={handleSearchSubmit} className="relative w-full md:w-72">
                        <input
                            type="text"
                            value={searchQuery}
                            onChange={(e) => setSearchQuery(e.target.value)}
                            placeholder="Filter by title..."
                            className="w-full pl-9 pr-8 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-sky-500"
                        />
                        <svg className="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {searchQuery && (
                            <button
                                type="button"
                                onClick={() => { setSearchQuery(''); router.get('/author/dashboard', { ...filters, search: '' }); }}
                                className="absolute right-2.5 top-2.5 text-xs text-slate-400 hover:text-slate-600"
                            >
                                ✕
                            </button>
                        )}
                    </form>
                </div>

                {/* Posts Table / List */}
                {posts.data.length > 0 ? (
                    <div className="rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left border-collapse">
                                <thead>
                                    <tr className="border-b border-slate-100 dark:border-white/[0.08] bg-slate-50/50 dark:bg-white/[0.02] text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                        <th className="py-3.5 px-6">Article</th>
                                        <th className="py-3.5 px-4">Category</th>
                                        <th className="py-3.5 px-4">Status</th>
                                        <th className="py-3.5 px-4 text-center">Reads</th>
                                        <th className="py-3.5 px-4 text-center">Likes</th>
                                        <th className="py-3.5 px-4">Date</th>
                                        <th className="py-3.5 pr-6 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100 dark:divide-white/[0.06] text-xs">
                                    {posts.data.map((post) => (
                                        <tr key={post.id} className="hover:bg-slate-50/60 dark:hover:bg-white/[0.02] transition">
                                            {/* Article Thumbnail & Title */}
                                            <td className="py-4 px-6">
                                                <div className="flex items-center gap-3">
                                                    <div className="w-14 h-10 rounded-lg bg-slate-800 overflow-hidden shrink-0">
                                                        <img
                                                            src={post.featured_image || 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=300&q=80'}
                                                            alt=""
                                                            className="w-full h-full object-cover"
                                                        />
                                                    </div>
                                                    <div className="min-w-0 max-w-sm">
                                                        <h4 className="font-bold text-slate-900 dark:text-white truncate">
                                                            {post.title}
                                                        </h4>
                                                        {post.sub_title && (
                                                            <p className="text-[11px] text-slate-400 truncate mt-0.5">
                                                                {post.sub_title}
                                                            </p>
                                                        )}
                                                    </div>
                                                </div>
                                            </td>

                                            {/* Category */}
                                            <td className="py-4 px-4 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                                {post.category?.name || post.category_name || '-'}
                                            </td>

                                            {/* Status Badge */}
                                            <td className="py-4 px-4 whitespace-nowrap">
                                                {post.status === 'published' ? (
                                                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                        <span className="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Published
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                        <span className="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                        Draft
                                                    </span>
                                                )}
                                            </td>

                                            {/* Views Metric */}
                                            <td className="py-4 px-4 text-center font-semibold text-slate-700 dark:text-slate-300">
                                                {post.views_count?.toLocaleString() || 0}
                                            </td>

                                            {/* Likes Metric */}
                                            <td className="py-4 px-4 text-center font-semibold text-pink-500">
                                                {post.likes_count?.toLocaleString() || 0}
                                            </td>

                                            {/* Date */}
                                            <td className="py-4 px-4 text-slate-400 whitespace-nowrap">
                                                {new Date(post.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                            </td>

                                            {/* Action Buttons */}
                                            <td className="py-4 pr-6 text-right whitespace-nowrap">
                                                <div className="flex items-center justify-end gap-1.5">
                                                    {post.status === 'published' && (
                                                        <Link
                                                            href={`/posts/${post.slug}`}
                                                            target="_blank"
                                                            className="p-1.5 rounded-lg text-slate-400 hover:text-sky-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                                                            title="View Live Article"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                            </svg>
                                                        </Link>
                                                    )}
                                                    <Link
                                                        href={`/author/posts/${post.id}/edit`}
                                                        className="p-1.5 rounded-lg text-slate-400 hover:text-indigo-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                                                        title="Edit Article"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </Link>
                                                    <button
                                                        type="button"
                                                        disabled={deletingPostId === post.id}
                                                        onClick={() => confirmDelete(post)}
                                                        className="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition disabled:opacity-50"
                                                        title="Delete Article"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        {posts.links && posts.links.length > 3 && (
                            <div className="px-6 py-4 border-t border-slate-100 dark:border-white/[0.08] flex items-center justify-between text-xs text-slate-400">
                                <span>Showing {posts.from} to {posts.to} of {posts.total}</span>
                                <div className="flex items-center gap-1">
                                    {posts.links.map((link, idx) => {
                                        if (!link.url) return null;
                                        return (
                                            <Link
                                                key={idx}
                                                href={link.url}
                                                preserveScroll
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                                className={`px-3 py-1.5 rounded-lg font-semibold transition ${
                                                    link.active
                                                        ? 'bg-sky-500 text-white'
                                                        : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/[0.05]'
                                                }`}
                                            />
                                        );
                                    })}
                                </div>
                            </div>
                        )}
                    </div>
                ) : (
                    /* Empty State */
                    <div className="py-16 text-center rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 p-8 shadow-sm">
                        <div className="w-16 h-16 mx-auto rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center mb-4">
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 className="text-lg font-bold text-slate-900 dark:text-white">No articles found</h3>
                        <p className="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                            You haven't written any articles yet, or no articles match your filter.
                        </p>
                        <Link
                            href="/author/posts/create"
                            className="inline-block mt-5 px-5 py-2.5 rounded-xl text-xs font-bold bg-sky-500 text-white hover:bg-sky-600 transition shadow-md shadow-sky-500/20"
                        >
                            Start Writing Your First Story
                        </Link>
                    </div>
                )}

            </div>
        </FrontendLayout>
    );
}
