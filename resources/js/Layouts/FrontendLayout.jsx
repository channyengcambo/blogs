import React, { useState, useEffect } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';

export default function FrontendLayout({ children, title, description, image }) {
    const { auth, flash, filters } = usePage().props;
    const [darkMode, setDarkMode] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [toast, setToast] = useState(null);

    // Initialize theme from localStorage or system preference
    useEffect(() => {
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = savedTheme === 'dark' || (!savedTheme && systemPrefersDark);
        
        setDarkMode(isDark);
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }, []);

    // Flash message listener
    useEffect(() => {
        if (flash?.success) {
            setToast({ type: 'success', message: flash.success });
            const timer = setTimeout(() => setToast(null), 4000);
            return () => clearTimeout(timer);
        }
        if (flash?.error) {
            setToast({ type: 'error', message: flash.error });
            const timer = setTimeout(() => setToast(null), 4000);
            return () => clearTimeout(timer);
        }
    }, [flash]);

    const toggleTheme = () => {
        const nextMode = !darkMode;
        setDarkMode(nextMode);
        if (nextMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    };

    return (
        <div className="min-h-screen flex flex-col bg-slate-50 dark:bg-[#0B0F17] text-slate-800 dark:text-slate-100 transition-colors duration-300 font-sans selection:bg-sky-500 selection:text-white">
            <Head>
                <title>{title ? `${title} — TechChronicle` : 'TechChronicle — Modern Software & AI Engineering Blog'}</title>
                <meta name="description" content={description || 'Deep dives into modern full-stack development, artificial intelligence, system architecture, and cutting-edge software engineering.'} />
                <meta property="og:title" content={title || 'TechChronicle'} />
                <meta property="og:description" content={description || 'Modern Software & AI Engineering Blog'} />
                {image && <meta property="og:image" content={image} />}
            </Head>

            {/* Notification Toast */}
            {toast && (
                <div className="fixed bottom-6 right-6 z-50 animate-bounce duration-300">
                    <div className={`px-4 py-3 rounded-xl shadow-2xl border text-sm font-medium flex items-center gap-3 backdrop-blur-md ${
                        toast.type === 'success' 
                            ? 'bg-emerald-500/90 text-white border-emerald-400/40 shadow-emerald-500/20' 
                            : 'bg-rose-500/90 text-white border-rose-400/40 shadow-rose-500/20'
                    }`}>
                        <span>{toast.message}</span>
                        <button onClick={() => setToast(null)} className="opacity-70 hover:opacity-100 text-xs">✕</button>
                    </div>
                </div>
            )}

            {/* Top Announcement Bar */}
            <div className="bg-gradient-to-r from-sky-600 via-indigo-600 to-purple-600 text-white text-xs font-medium py-1.5 px-4 text-center">
                <span>🚀 Welcome to the next-generation TechChronicle blog platform. Clean code, AI systems, and modern web architectures.</span>
            </div>

            {/* Primary Navigation Header */}
            <header className="sticky top-0 z-40 backdrop-blur-xl bg-white/80 dark:bg-[#0B0F17]/80 border-b border-slate-200/80 dark:border-white/[0.08] transition-colors">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        
                        {/* Logo & Brand */}
                        <div className="flex items-center gap-8">
                            <Link href="/" className="flex items-center gap-3 group">
                                <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/20 group-hover:scale-105 transition-transform duration-200">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div className="flex flex-col">
                                    <span className="font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-900 via-sky-900 to-indigo-900 dark:from-white dark:via-sky-200 dark:to-indigo-200 bg-clip-text text-transparent">
                                        TechChronicle
                                    </span>
                                    <span className="text-[10px] uppercase tracking-widest text-sky-500 dark:text-sky-400 font-bold -mt-1">
                                        Engineering & AI
                                    </span>
                                </div>
                            </Link>

                            {/* Desktop Nav Links */}
                            <nav className="hidden md:flex items-center gap-1">
                                <Link
                                    href="/"
                                    className="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-slate-100 dark:hover:bg-white/[0.05] transition"
                                >
                                    Home
                                </Link>
                                <Link
                                    href="/?sort=popular"
                                    className="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-slate-100 dark:hover:bg-white/[0.05] transition"
                                >
                                    Popular
                                </Link>
                                <Link
                                    href="/?sort=trending"
                                    className="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-slate-100 dark:hover:bg-white/[0.05] transition"
                                >
                                    Trending
                                </Link>
                            </nav>
                        </div>

                        {/* Right Actions: Theme Toggle, Search, Auth Button */}
                        <div className="flex items-center gap-3">
                            
                            {/* Dark Mode Toggle */}
                            <button
                                type="button"
                                onClick={toggleTheme}
                                className="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.08] transition"
                                title={darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
                            >
                                {darkMode ? (
                                    <svg className="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                ) : (
                                    <svg className="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                )}
                            </button>

                            {/* Admin or Sign In Link */}
                            {auth?.user ? (
                                <div className="flex items-center gap-2">
                                    {auth.user.is_admin ? (
                                        <a
                                            href="/admin"
                                            className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20 hover:bg-sky-500/20 transition"
                                        >
                                            <span className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Admin Dashboard
                                        </a>
                                    ) : (
                                        <a
                                            href="/dashboard"
                                            className="px-3 py-1.5 text-xs font-medium rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-dark-800 transition"
                                        >
                                            Dashboard
                                        </a>
                                    )}
                                </div>
                            ) : (
                                <a
                                    href="/login"
                                    className="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 transition shadow-sm"
                                >
                                    <span>Sign In</span>
                                    <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            )}

                            {/* Mobile Hamburger Toggle */}
                            <button
                                type="button"
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                className="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-white/[0.08]"
                            >
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {mobileMenuOpen ? (
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                    ) : (
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                    )}
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile Menu Dropdown */}
                {mobileMenuOpen && (
                    <div className="md:hidden border-t border-slate-200/80 dark:border-white/[0.08] bg-white/95 dark:bg-[#0B0F17]/95 px-4 pt-3 pb-4 space-y-2">
                        <Link
                            href="/"
                            onClick={() => setMobileMenuOpen(false)}
                            className="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.05]"
                        >
                            Home
                        </Link>
                        <Link
                            href="/?sort=popular"
                            onClick={() => setMobileMenuOpen(false)}
                            className="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.05]"
                        >
                            Popular Articles
                        </Link>
                        <Link
                            href="/?sort=trending"
                            onClick={() => setMobileMenuOpen(false)}
                            className="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/[0.05]"
                        >
                            Trending Articles
                        </Link>
                        {auth?.user?.is_admin && (
                            <a
                                href="/admin"
                                className="block px-3 py-2 rounded-lg text-base font-semibold text-sky-600 dark:text-sky-400 bg-sky-500/10"
                            >
                                Admin Dashboard
                            </a>
                        )}
                    </div>
                )}
            </header>

            {/* Main Content Body */}
            <main className="flex-1">
                {children}
            </main>

            {/* Footer */}
            <footer className="mt-20 border-t border-slate-200/80 dark:border-white/[0.08] bg-white dark:bg-[#070A0F] transition-colors">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-10">
                        {/* Brand Column */}
                        <div className="md:col-span-2 space-y-4">
                            <div className="flex items-center gap-3">
                                <div className="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <span className="font-extrabold text-xl tracking-tight bg-gradient-to-r from-slate-900 to-indigo-900 dark:from-white dark:to-sky-200 bg-clip-text text-transparent">
                                    TechChronicle
                                </span>
                            </div>
                            <p className="text-sm text-slate-600 dark:text-slate-400 max-w-md leading-relaxed">
                                High-impact engineering articles, architectural patterns, and AI research designed for software engineers, developers, and technology architects.
                            </p>
                            <div className="flex items-center gap-3 pt-2">
                                <a href="https://github.com" target="_blank" rel="noreferrer" className="p-2 rounded-lg text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.05] transition">
                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fillRule="evenodd" clipRule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" /></svg>
                                </a>
                                <a href="https://twitter.com" target="_blank" rel="noreferrer" className="p-2 rounded-lg text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/[0.05] transition">
                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                                </a>
                            </div>
                        </div>

                        {/* Navigation Columns */}
                        <div>
                            <h4 className="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">
                                Navigation
                            </h4>
                            <ul className="space-y-2.5 text-sm text-slate-600 dark:text-slate-400">
                                <li><Link href="/" className="hover:text-sky-500 transition">Latest Articles</Link></li>
                                <li><Link href="/?sort=popular" className="hover:text-sky-500 transition">Most Popular</Link></li>
                                <li><Link href="/?sort=trending" className="hover:text-sky-500 transition">Trending</Link></li>
                                <li><a href="/login" className="hover:text-sky-500 transition">Author Sign In</a></li>
                            </ul>
                        </div>

                        <div>
                            <h4 className="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-4">
                                Architecture
                            </h4>
                            <ul className="space-y-2.5 text-sm text-slate-600 dark:text-slate-400">
                                <li className="flex items-center gap-1.5">
                                    <span className="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Zero-Join Read Model</span>
                                </li>
                                <li className="flex items-center gap-1.5">
                                    <span className="w-2 h-2 rounded-full bg-sky-500"></span>
                                    <span>Inertia.js + React 18</span>
                                </li>
                                <li className="flex items-center gap-1.5">
                                    <span className="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    <span>Laravel 12 REST Engine</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="mt-12 pt-8 border-t border-slate-200/60 dark:border-white/[0.06] flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                        <p>© {new Date().getFullYear()} TechChronicle. Engineered with Laravel 12 & React.</p>
                        <div className="flex items-center gap-6">
                            <span>Privacy Policy</span>
                            <span>Terms of Service</span>
                            <span>RSS Feed</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
