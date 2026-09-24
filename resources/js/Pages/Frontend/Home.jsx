import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import FrontendLayout from '@/Layouts/FrontendLayout';

export default function Home({
    posts,
    featuredPost,
    trendingPosts,
    categories,
    tags,
    filters,
    activeCategory,
    activeTag,
}) {
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [newsletterEmail, setNewsletterEmail] = useState('');
    const [newsletterStatus, setNewsletterStatus] = useState(null);

    // Apply search with debounce or submit
    const handleSearchSubmit = (e) => {
        e.preventDefault();
        router.get('/', {
            ...filters,
            search: searchQuery,
            page: 1,
        }, { preserveState: true, preserveScroll: true });
    };

    const handleCategoryClick = (categorySlug) => {
        router.get('/', {
            ...filters,
            category: filters.category === categorySlug ? '' : categorySlug,
            page: 1,
        }, { preserveState: true, preserveScroll: true });
    };

    const handleTagClick = (tagSlug) => {
        router.get('/', {
            ...filters,
            tag: filters.tag === tagSlug ? '' : tagSlug,
            page: 1,
        }, { preserveState: true, preserveScroll: true });
    };

    const handleSortChange = (newSort) => {
        router.get('/', {
            ...filters,
            sort: newSort,
            page: 1,
        }, { preserveState: true, preserveScroll: true });
    };

    const clearAllFilters = () => {
        setSearchQuery('');
        router.get('/', {}, { preserveState: true });
    };

    const handleNewsletterSubmit = async (e) => {
        e.preventDefault();
        if (!newsletterEmail) return;
        setNewsletterStatus('submitting');
        try {
            await router.post('/newsletter/subscribe', { email: newsletterEmail }, {
                preserveScroll: true,
                onSuccess: () => {
                    setNewsletterStatus('success');
                    setNewsletterEmail('');
                },
                onError: () => {
                    setNewsletterStatus('error');
                }
            });
        } catch {
            setNewsletterStatus('error');
        }
    };

    const isFiltered = Boolean(filters.search || filters.category || filters.tag || (filters.sort && filters.sort !== 'latest'));

    return (
        <FrontendLayout
            title={activeCategory ? `${activeCategory.name} Articles` : (activeTag ? `#${activeTag.name} Articles` : 'Engineering & AI Blog')}
            description="High-performance articles on distributed systems, AI architectures, full-stack frameworks, and clean code."
        >
            {/* Ambient Background Gradient Blur */}
            <div className="relative overflow-hidden">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[450px] bg-gradient-to-tr from-sky-500/10 via-indigo-500/10 to-purple-500/10 blur-3xl pointer-events-none -z-10" />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
                    
                    {/* HERO SECTION (Shown when not heavily filtered) */}
                    {!isFiltered && featuredPost && (
                        <div className="mb-14">
                            {/* Section Header */}
                            <div className="flex items-center gap-2 mb-4">
                                <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                                    <span className="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span>
                                    Featured Deep Dive
                                </span>
                            </div>

                            {/* Main Featured Hero Card */}
                            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                                <div className="lg:col-span-8 group relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-xl shadow-slate-200/40 dark:shadow-black/40 flex flex-col">
                                    <div className="relative aspect-[16/9] sm:aspect-[21/9] lg:aspect-[16/9] w-full overflow-hidden bg-slate-800">
                                        <img
                                            src={featuredPost.featured_image || 'https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=1200&q=80'}
                                            alt={featuredPost.title}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                                        
                                        {/* Overlay Content */}
                                        <div className="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                                            <div className="flex flex-wrap items-center gap-2 mb-3">
                                                {featuredPost.category_name && (
                                                    <span className="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-sky-500 text-white shadow-md shadow-sky-500/30">
                                                        {featuredPost.category_name}
                                                    </span>
                                                )}
                                                <span className="text-xs text-slate-300 font-medium flex items-center gap-1 bg-black/40 backdrop-blur-md px-2.5 py-1 rounded-md">
                                                    <svg className="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {featuredPost.reading_time || 5} min read
                                                </span>
                                            </div>

                                            <Link href={`/posts/${featuredPost.slug}`}>
                                                <h1 className="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-tight hover:text-sky-300 transition group-hover:translate-x-0.5">
                                                    {featuredPost.title}
                                                </h1>
                                            </Link>

                                            {featuredPost.sub_title && (
                                                <p className="mt-2 text-sm sm:text-base text-slate-300 line-clamp-2 max-w-2xl font-normal">
                                                    {featuredPost.sub_title}
                                                </p>
                                            )}

                                            <div className="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                                                <div className="flex items-center gap-2.5">
                                                    <div className="w-8 h-8 rounded-full bg-gradient-to-tr from-sky-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shadow-md">
                                                        {(featuredPost.author_name || 'A').charAt(0).toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <p className="text-xs font-bold text-white">
                                                            {featuredPost.author_name || 'Admin'}
                                                        </p>
                                                        <p className="text-[11px] text-slate-400">
                                                            {featuredPost.published_at ? new Date(featuredPost.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Recently'}
                                                        </p>
                                                    </div>
                                                </div>

                                                <Link
                                                    href={`/posts/${featuredPost.slug}`}
                                                    className="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-900 hover:bg-sky-400 hover:text-white transition shadow-lg shadow-black/20"
                                                >
                                                    <span>Read Article</span>
                                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Right Side: Trending Stories Card */}
                                <div className="lg:col-span-4 rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 p-6 shadow-xl shadow-slate-200/40 dark:shadow-black/40 flex flex-col justify-between">
                                    <div>
                                        <div className="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-white/[0.08]">
                                            <div className="flex items-center gap-2">
                                                <span className="text-lg">🔥</span>
                                                <h3 className="font-extrabold text-sm uppercase tracking-wider text-slate-900 dark:text-white">
                                                    Trending Reads
                                                </h3>
                                            </div>
                                            <span className="text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                                Top impact
                                            </span>
                                        </div>

                                        <div className="divide-y divide-slate-100 dark:divide-white/[0.06] mt-1">
                                            {trendingPosts.slice(0, 3).map((item, idx) => (
                                                <div key={item.id} className="py-4 group/item">
                                                    <div className="flex items-start gap-3">
                                                        <span className="text-2xl font-black text-slate-300 dark:text-slate-700 group-hover/item:text-sky-500 transition">
                                                            0{idx + 1}
                                                        </span>
                                                        <div className="flex-1 min-w-0">
                                                            {item.category_name && (
                                                                <span className="text-[10px] font-bold text-sky-600 dark:text-sky-400 uppercase tracking-wide">
                                                                    {item.category_name}
                                                                </span>
                                                            )}
                                                            <Link href={`/posts/${item.slug}`}>
                                                                <h4 className="text-sm font-bold text-slate-900 dark:text-slate-100 group-hover/item:text-sky-500 transition line-clamp-2 mt-0.5">
                                                                    {item.title}
                                                                </h4>
                                                            </Link>
                                                            <div className="flex items-center gap-3 mt-1.5 text-[11px] text-slate-500 dark:text-slate-400">
                                                                <span>{item.reading_time || 4}m read</span>
                                                                <span>•</span>
                                                                <span className="flex items-center gap-1">
                                                                    <svg className="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                    </svg>
                                                                    {item.views_count?.toLocaleString()}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>

                                    <div className="pt-4 border-t border-slate-100 dark:border-white/[0.08]">
                                        <Link
                                            href="/?sort=popular"
                                            className="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center block bg-slate-100 dark:bg-white/[0.05] text-slate-800 dark:text-slate-200 hover:bg-sky-500 hover:text-white transition"
                                        >
                                            View Top 10 Popular Stories →
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* TOPIC / CATEGORY PILL SELECTOR */}
                    <div className="mb-8">
                        <div className="flex items-center justify-between mb-3">
                            <h2 className="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Explore by Category
                            </h2>
                            {isFiltered && (
                                <button
                                    onClick={clearAllFilters}
                                    className="text-xs font-semibold text-rose-500 hover:underline flex items-center gap-1"
                                >
                                    <span>Clear all filters</span>
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            )}
                        </div>

                        <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                            <button
                                onClick={() => handleCategoryClick('')}
                                className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all shadow-sm ${
                                    !filters.category
                                        ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-slate-900/10'
                                        : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-white/10 hover:border-sky-500'
                                }`}
                            >
                                All Articles
                            </button>

                            {categories.map((cat) => {
                                const isActive = filters.category === cat.slug;
                                return (
                                    <button
                                        key={cat.id}
                                        onClick={() => handleCategoryClick(cat.slug)}
                                        className={`px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap flex items-center gap-2 transition-all shadow-sm ${
                                            isActive
                                                ? 'bg-sky-500 text-white shadow-sky-500/20'
                                                : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-white/10 hover:border-sky-500'
                                        }`}
                                    >
                                        <span>{cat.name}</span>
                                        <span className={`text-[10px] px-1.5 py-0.5 rounded-md ${
                                            isActive ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-white/[0.08] text-slate-500 dark:text-slate-400'
                                        }`}>
                                            {cat.posts_count}
                                        </span>
                                    </button>
                                );
                            })}
                        </div>
                    </div>

                    {/* SEARCH, SORT, AND FILTER TOOLBAR */}
                    <div className="mb-8 p-4 rounded-2xl bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-white/10 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                        
                        {/* Search Input Form */}
                        <form onSubmit={handleSearchSubmit} className="relative w-full md:w-80">
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Search articles, topics, keywords..."
                                className="w-full pl-10 pr-9 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 transition"
                            />
                            <svg className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {searchQuery && (
                                <button
                                    type="button"
                                    onClick={() => { setSearchQuery(''); router.get('/', { ...filters, search: '' }); }}
                                    className="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-white text-xs"
                                >
                                    ✕
                                </button>
                            )}
                        </form>

                        {/* Active Filter Badges */}
                        <div className="flex flex-wrap items-center gap-2 text-xs">
                            {filters.category && (
                                <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 font-semibold border border-sky-500/20">
                                    <span>Category: {activeCategory?.name || filters.category}</span>
                                    <button onClick={() => handleCategoryClick(filters.category)} className="hover:text-rose-500">✕</button>
                                </span>
                            )}
                            {filters.tag && (
                                <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 font-semibold border border-purple-500/20">
                                    <span>Tag: #{filters.tag}</span>
                                    <button onClick={() => handleTagClick(filters.tag)} className="hover:text-rose-500">✕</button>
                                </span>
                            )}
                            {filters.search && (
                                <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 font-semibold border border-amber-500/20">
                                    <span>Query: "{filters.search}"</span>
                                    <button onClick={() => { setSearchQuery(''); router.get('/', { ...filters, search: '' }); }} className="hover:text-rose-500">✕</button>
                                </span>
                            )}
                        </div>

                        {/* Sorting Dropdown */}
                        <div className="flex items-center gap-2 text-xs text-slate-500 w-full md:w-auto justify-end">
                            <span className="font-medium whitespace-nowrap">Sort by:</span>
                            <select
                                value={filters.sort || 'latest'}
                                onChange={(e) => handleSortChange(e.target.value)}
                                className="py-1.5 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 font-semibold focus:outline-none focus:ring-1 focus:ring-sky-500"
                            >
                                <option value="latest">Latest Published</option>
                                <option value="popular">Most Popular</option>
                                <option value="trending">Trending (Likes)</option>
                                <option value="oldest">Oldest First</option>
                            </select>
                        </div>
                    </div>

                    {/* ARTICLES GRID */}
                    {posts.data.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {posts.data.map((post) => (
                                <article
                                    key={post.id}
                                    className="group rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col hover:-translate-y-1"
                                >
                                    {/* Thumbnail Image */}
                                    <div className="relative aspect-[16/10] w-full bg-slate-800 overflow-hidden">
                                        <img
                                            src={post.featured_image || 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80'}
                                            alt={post.title}
                                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                            loading="lazy"
                                        />
                                        <div className="absolute top-3 left-3 flex items-center gap-2">
                                            {post.category_name && (
                                                <button
                                                    type="button"
                                                    onClick={(e) => { e.preventDefault(); handleCategoryClick(post.category_slug); }}
                                                    className="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-900/80 backdrop-blur-md text-white border border-white/10 hover:bg-sky-500 transition"
                                                >
                                                    {post.category_name}
                                                </button>
                                            )}
                                        </div>

                                        <div className="absolute bottom-3 right-3 px-2 py-0.5 rounded-md text-[10px] font-medium bg-black/60 backdrop-blur-md text-slate-200 flex items-center gap-1">
                                            <svg className="w-3 h-3 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{post.reading_time || 3} min read</span>
                                        </div>
                                    </div>

                                    {/* Content Card Body */}
                                    <div className="p-6 flex-1 flex flex-col justify-between">
                                        <div>
                                            <div className="text-[11px] text-slate-400 font-medium mb-2">
                                                {post.published_at ? new Date(post.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Recently'}
                                            </div>

                                            <Link href={`/posts/${post.slug}`}>
                                                <h3 className="text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-sky-500 transition leading-snug line-clamp-2">
                                                    {post.title}
                                                </h3>
                                            </Link>

                                            <p className="mt-2 text-xs text-slate-600 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                                {post.sub_title || post.body?.replace(/<[^>]*>?/gm, '').substring(0, 140) + '...'}
                                            </p>
                                        </div>

                                        {/* Footer: Author & Metrics */}
                                        <div className="mt-6 pt-4 border-t border-slate-100 dark:border-white/[0.08] flex items-center justify-between text-xs">
                                            <div className="flex items-center gap-2">
                                                <div className="w-6 h-6 rounded-full bg-gradient-to-tr from-sky-400 to-indigo-500 flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                                    {(post.author_name || 'A').charAt(0).toUpperCase()}
                                                </div>
                                                <span className="font-semibold text-slate-700 dark:text-slate-300 text-[11px] truncate max-w-[100px]">
                                                    {post.author_name || 'Author'}
                                                </span>
                                            </div>

                                            <div className="flex items-center gap-3 text-slate-400 text-[11px]">
                                                <span className="flex items-center gap-1" title="Views">
                                                    <svg className="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {post.views_count?.toLocaleString()}
                                                </span>
                                                <span className="flex items-center gap-1" title="Likes">
                                                    <svg className="w-3.5 h-3.5 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clipRule="evenodd" />
                                                    </svg>
                                                    {post.likes_count?.toLocaleString()}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            ))}
                        </div>
                    ) : (
                        /* Empty Results State */
                        <div className="py-16 text-center rounded-3xl bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-white/10 p-8">
                            <div className="w-16 h-16 mx-auto rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center mb-4">
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <h3 className="text-lg font-bold text-slate-900 dark:text-white">No articles found</h3>
                            <p className="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                                We couldn't find any articles matching your search criteria. Try a different query or clear your filters.
                            </p>
                            <button
                                onClick={clearAllFilters}
                                className="mt-5 px-4 py-2 rounded-xl text-xs font-semibold bg-sky-500 text-white hover:bg-sky-600 transition shadow-md shadow-sky-500/20"
                            >
                                Reset All Filters
                            </button>
                        </div>
                    )}

                    {/* PAGINATION BAR */}
                    {posts.links && posts.links.length > 3 && (
                        <div className="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200/80 dark:border-white/10 shadow-sm text-xs">
                            <div className="text-slate-500 dark:text-slate-400">
                                Showing <span className="font-semibold text-slate-900 dark:text-white">{posts.from || 0}</span> to <span className="font-semibold text-slate-900 dark:text-white">{posts.to || 0}</span> of <span className="font-semibold text-slate-900 dark:text-white">{posts.total}</span> articles
                            </div>

                            <div className="flex items-center gap-1.5">
                                {posts.links.map((link, i) => {
                                    if (!link.url) {
                                        return (
                                            <span
                                                key={i}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                                className="px-3 py-1.5 rounded-lg text-slate-300 dark:text-slate-600 opacity-60 text-xs"
                                            />
                                        );
                                    }
                                    return (
                                        <Link
                                            key={i}
                                            href={link.url}
                                            preserveScroll
                                            preserveState
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            className={`px-3 py-1.5 rounded-lg font-semibold text-xs transition ${
                                                link.active
                                                    ? 'bg-sky-500 text-white shadow-md shadow-sky-500/20'
                                                    : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/[0.08]'
                                            }`}
                                        />
                                    );
                                })}
                            </div>
                        </div>
                    )}

                    {/* POPULAR TAGS CLOUD */}
                    {tags && tags.length > 0 && (
                        <div className="mt-16 pt-8 border-t border-slate-200/80 dark:border-white/[0.08]">
                            <h3 className="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4">
                                Popular Topics & Tags
                            </h3>
                            <div className="flex flex-wrap items-center gap-2">
                                {tags.map((tag) => (
                                    <button
                                        key={tag.id}
                                        onClick={() => handleTagClick(tag.slug)}
                                        className={`px-3 py-1.5 rounded-xl text-xs font-medium transition ${
                                            filters.tag === tag.slug
                                                ? 'bg-purple-600 text-white shadow-md shadow-purple-500/20'
                                                : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:border-purple-500'
                                        }`}
                                    >
                                        #{tag.name} <span className="opacity-60 text-[10px]">({tag.posts_count})</span>
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* NEWSLETTER CTA BOX */}
                    <div className="mt-16 relative rounded-3xl overflow-hidden p-8 sm:p-12 bg-gradient-to-r from-sky-600 via-indigo-600 to-purple-700 text-white shadow-2xl">
                        <div className="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none -mr-20 -mt-20"></div>
                        
                        <div className="relative max-w-2xl">
                            <span className="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-white/20 backdrop-blur-md mb-4">
                                📬 Stay Ahead
                            </span>
                            <h2 className="text-2xl sm:text-3xl font-extrabold tracking-tight">
                                Delivering deep-dive engineering insights straight to your inbox
                            </h2>
                            <p className="mt-2 text-sm text-sky-100 leading-relaxed">
                                Join 5,000+ software architects and engineers who receive our bi-weekly breakdown of system design patterns, distributed algorithms, and AI tooling.
                            </p>

                            <form onSubmit={handleNewsletterSubmit} className="mt-6 flex flex-col sm:flex-row gap-3">
                                <input
                                    type="email"
                                    required
                                    value={newsletterEmail}
                                    onChange={(e) => setNewsletterEmail(e.target.value)}
                                    placeholder="Enter your work email address..."
                                    className="flex-1 px-4 py-3 rounded-xl text-xs bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-sky-200 focus:outline-none focus:ring-2 focus:ring-white"
                                />
                                <button
                                    type="submit"
                                    disabled={newsletterStatus === 'submitting'}
                                    className="px-6 py-3 rounded-xl text-xs font-bold bg-white text-slate-900 hover:bg-sky-100 transition shadow-lg shrink-0 disabled:opacity-75"
                                >
                                    {newsletterStatus === 'submitting' ? 'Subscribing...' : 'Subscribe Free'}
                                </button>
                            </form>

                            {newsletterStatus === 'success' && (
                                <p className="mt-3 text-xs text-emerald-200 font-semibold flex items-center gap-1.5">
                                    ✓ You're in! Check your inbox shortly.
                                </p>
                            )}
                            {newsletterStatus === 'error' && (
                                <p className="mt-3 text-xs text-rose-200 font-semibold">
                                    Oops, something went wrong. Please check your email and try again.
                                </p>
                            )}
                            <p className="mt-3 text-[11px] text-sky-200/80">
                                Zero spam. Unsubscribe anytime with 1 click.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </FrontendLayout>
    );
}
