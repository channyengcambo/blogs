import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import FrontendLayout from '@/Layouts/FrontendLayout';

export default function PostEditor({ post, categories, tags }) {
    const isEditing = Boolean(post?.id);
    const [editorTab, setEditorTab] = useState('write'); // 'write' or 'preview'

    const { data, setData, post: submitPost, put: submitPut, processing, errors } = useForm({
        title: post?.title || '',
        sub_title: post?.sub_title || '',
        category_id: post?.category_id || '',
        body: post?.body || '',
        featured_image: post?.featured_image || '',
        status: post?.status || 'published',
        tags: post?.tag_ids || [],
    });

    const handleTagToggle = (tagId) => {
        if (data.tags.includes(tagId)) {
            setData('tags', data.tags.filter((id) => id !== tagId));
        } else {
            setData('tags', [...data.tags, tagId]);
        }
    };

    const handleSubmit = (chosenStatus) => {
        // Update status immediately then submit
        const payload = { ...data, status: chosenStatus };
        setData('status', chosenStatus);

        if (isEditing) {
            submitPut(`/author/posts/${post.id}`);
        } else {
            submitPost('/author/posts');
        }
    };

    // Calculate reading time
    const wordCount = data.body.trim() ? data.body.trim().split(/\s+/).length : 0;
    const readingTime = Math.max(1, Math.ceil(wordCount / 200));

    // Generate slug preview
    const slugPreview = data.title
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');

    const insertFormatting = (prefix, suffix = '') => {
        const textarea = document.getElementById('article-body-textarea');
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selected = text.substring(start, end);
        const replacement = prefix + selected + suffix;

        const newText = text.substring(0, start) + replacement + text.substring(end);
        setData('body', newText);

        setTimeout(() => {
            textarea.focus();
            textarea.setSelectionRange(start + prefix.length, end + prefix.length);
        }, 50);
    };

    return (
        <FrontendLayout
            title={isEditing ? `Edit: ${data.title || 'Article'}` : 'Write New Article'}
            description="Authoring studio for modern software engineering and AI architectures."
        >
            <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                {/* Top Action Bar */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200/80 dark:border-white/10">
                    <div className="flex items-center gap-3">
                        <Link
                            href="/author/dashboard"
                            className="p-2 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white bg-slate-100 dark:bg-slate-800 transition"
                            title="Back to Dashboard"
                        >
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div>
                            <span className="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                {isEditing ? 'Editing Article' : 'Drafting New Story'}
                            </span>
                            <h1 className="text-xl font-black text-slate-900 dark:text-white">
                                {isEditing ? 'Update Article' : 'Compose Story'}
                            </h1>
                        </div>
                    </div>

                    <div className="flex items-center gap-3">
                        <button
                            type="button"
                            disabled={processing}
                            onClick={() => handleSubmit('draft')}
                            className="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition disabled:opacity-50"
                        >
                            Save as Draft
                        </button>

                        <button
                            type="button"
                            disabled={processing}
                            onClick={() => handleSubmit('published')}
                            className="px-5 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-sky-500 to-indigo-600 text-white hover:from-sky-600 hover:to-indigo-700 transition shadow-lg shadow-sky-500/25 disabled:opacity-50"
                        >
                            {processing ? 'Publishing...' : (isEditing && data.status === 'published' ? 'Update & Publish' : 'Publish Live Now')}
                        </button>
                    </div>
                </div>

                {/* Form Fields */}
                <form onSubmit={(e) => { e.preventDefault(); handleSubmit(data.status); }} className="mt-8 space-y-6">
                    
                    {/* Title */}
                    <div>
                        <input
                            type="text"
                            value={data.title}
                            onChange={(e) => setData('title', e.target.value)}
                            placeholder="Article Title..."
                            className="w-full text-2xl sm:text-4xl font-black bg-transparent border-0 border-b border-slate-200/80 dark:border-white/10 pb-3 text-slate-900 dark:text-white placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-0 focus:border-sky-500 transition"
                        />
                        {errors.title && <p className="text-xs text-rose-500 font-semibold mt-1.5">{errors.title}</p>}
                        
                        {slugPreview && (
                            <p className="mt-1.5 text-[11px] text-slate-400 font-mono">
                                🔗 Public URL: /posts/<span className="text-sky-500 font-bold">{slugPreview}</span>
                            </p>
                        )}
                    </div>

                    {/* Subtitle / Excerpt */}
                    <div>
                        <input
                            type="text"
                            value={data.sub_title}
                            onChange={(e) => setData('sub_title', e.target.value)}
                            placeholder="Subtitle or short overview (optional, shows on social cards & search snippets)..."
                            className="w-full text-sm sm:text-base font-normal bg-transparent border-0 border-b border-slate-200/80 dark:border-white/10 pb-2 text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:outline-none focus:ring-0 focus:border-sky-500 transition"
                        />
                        {errors.sub_title && <p className="text-xs text-rose-500 font-semibold mt-1.5">{errors.sub_title}</p>}
                    </div>

                    {/* Category & Featured Image Row */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        {/* Category */}
                        <div>
                            <label className="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Primary Category
                            </label>
                            <select
                                value={data.category_id}
                                onChange={(e) => setData('category_id', e.target.value)}
                                className="w-full py-2.5 px-3.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500"
                            >
                                <option value="">Select a Category...</option>
                                {categories.map((cat) => (
                                    <option key={cat.id} value={cat.id}>
                                        {cat.name}
                                    </option>
                                ))}
                            </select>
                            {errors.category_id && <p className="text-xs text-rose-500 font-semibold mt-1">{errors.category_id}</p>}
                        </div>

                        {/* Featured Image URL */}
                        <div>
                            <label className="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Cover Image URL
                            </label>
                            <div className="flex gap-2">
                                <input
                                    type="url"
                                    value={data.featured_image}
                                    onChange={(e) => setData('featured_image', e.target.value)}
                                    placeholder="https://images.unsplash.com/..."
                                    className="flex-1 py-2 px-3.5 rounded-xl text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500"
                                />
                                {data.featured_image && (
                                    <button
                                        type="button"
                                        onClick={() => setData('featured_image', '')}
                                        className="px-2.5 py-1 text-xs text-slate-400 hover:text-rose-500 border border-slate-200 dark:border-white/10 rounded-xl"
                                    >
                                        ✕
                                    </button>
                                )}
                            </div>
                            {errors.featured_image && <p className="text-xs text-rose-500 font-semibold mt-1">{errors.featured_image}</p>}
                        </div>
                    </div>

                    {/* Image Preview Box */}
                    {data.featured_image && (
                        <div className="rounded-2xl overflow-hidden aspect-[21/9] max-h-56 bg-slate-900 border border-slate-200/80 dark:border-white/10 relative">
                            <img
                                src={data.featured_image}
                                alt="Cover preview"
                                className="w-full h-full object-cover"
                                onError={(e) => { e.target.style.display = 'none'; }}
                            />
                            <span className="absolute bottom-2 right-2 text-[10px] font-bold bg-black/70 text-white px-2 py-0.5 rounded-md backdrop-blur-md">
                                Live Cover Preview
                            </span>
                        </div>
                    )}

                    {/* Tags Multi-select */}
                    <div>
                        <label className="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Taxonomy Tags (Select all that apply)
                        </label>
                        <div className="flex flex-wrap gap-2">
                            {tags.map((tag) => {
                                const isSelected = data.tags.includes(tag.id);
                                return (
                                    <button
                                        key={tag.id}
                                        type="button"
                                        onClick={() => handleTagToggle(tag.id)}
                                        className={`px-3 py-1.5 rounded-xl text-xs font-semibold transition ${
                                            isSelected
                                                ? 'bg-purple-600 text-white shadow-md shadow-purple-500/20'
                                                : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:border-purple-400'
                                        }`}
                                    >
                                        #{tag.name} {isSelected && '✓'}
                                    </button>
                                );
                            })}
                        </div>
                    </div>

                    {/* Editor Toolbar & Tab Switcher */}
                    <div className="pt-4 border-t border-slate-200/80 dark:border-white/10">
                        <div className="flex items-center justify-between pb-3">
                            {/* Write vs Preview Tabs */}
                            <div className="flex items-center gap-2">
                                <button
                                    type="button"
                                    onClick={() => setEditorTab('write')}
                                    className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition ${
                                        editorTab === 'write'
                                            ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900'
                                            : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'
                                    }`}
                                >
                                    ✍️ Write
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setEditorTab('preview')}
                                    className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition ${
                                        editorTab === 'preview'
                                            ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900'
                                            : 'text-slate-500 hover:text-slate-900 dark:hover:text-white'
                                    }`}
                                >
                                    👁️ Preview
                                </button>
                            </div>

                            {/* Formatting Helpers */}
                            {editorTab === 'write' && (
                                <div className="hidden sm:flex items-center gap-1.5 text-xs text-slate-500">
                                    <button type="button" onClick={() => insertFormatting('## ')} className="px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 font-bold" title="H2 Heading">H2</button>
                                    <button type="button" onClick={() => insertFormatting('### ')} className="px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 font-bold" title="H3 Heading">H3</button>
                                    <button type="button" onClick={() => insertFormatting('**', '**')} className="px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 font-bold" title="Bold">B</button>
                                    <button type="button" onClick={() => insertFormatting('*', '*')} className="px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 italic" title="Italic">I</button>
                                    <button type="button" onClick={() => insertFormatting('> ')} className="px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800" title="Quote">Quote</button>
                                    <button type="button" onClick={() => insertFormatting('```\n', '\n```')} className="px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800 font-mono" title="Code Block">Code</button>
                                </div>
                            )}

                            {/* Word count & Reading Time */}
                            <div className="text-[11px] text-slate-400 font-medium">
                                <span>{wordCount} words</span>
                                <span className="mx-2">•</span>
                                <span>~{readingTime} min read</span>
                            </div>
                        </div>

                        {/* Editor Tab Content */}
                        {editorTab === 'write' ? (
                            <div>
                                <textarea
                                    id="article-body-textarea"
                                    rows={18}
                                    value={data.body}
                                    onChange={(e) => setData('body', e.target.value)}
                                    placeholder="Write your article in markdown or plain text... Double line breaks create new paragraphs. Use ## for headings, > for quotes, and ``` for code blocks."
                                    className="w-full p-4 rounded-2xl text-sm font-sans bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 leading-relaxed font-normal shadow-inner"
                                />
                                {errors.body && <p className="text-xs text-rose-500 font-semibold mt-1">{errors.body}</p>}
                            </div>
                        ) : (
                            /* Preview Tab */
                            <div className="p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 min-h-[350px] shadow-sm">
                                {data.title && (
                                    <h1 className="text-3xl font-black text-slate-900 dark:text-white mb-2">
                                        {data.title}
                                    </h1>
                                )}
                                {data.sub_title && (
                                    <p className="text-base text-slate-500 mb-6 italic">
                                        {data.sub_title}
                                    </p>
                                )}
                                <div className="space-y-4 text-slate-700 dark:text-slate-300 text-base leading-relaxed">
                                    {data.body ? (
                                        data.body.split('\n\n').map((para, i) => {
                                            if (!para.trim()) return null;
                                            if (para.startsWith('### ')) {
                                                return <h3 key={i} className="text-lg font-bold text-slate-900 dark:text-white pt-3">{para.replace('### ', '')}</h3>;
                                            }
                                            if (para.startsWith('## ')) {
                                                return <h2 key={i} className="text-xl font-bold text-slate-900 dark:text-white pt-4 pb-1 border-b border-slate-100 dark:border-white/[0.08]">{para.replace('## ', '')}</h2>;
                                            }
                                            if (para.startsWith('> ')) {
                                                return <blockquote key={i} className="pl-4 border-l-4 border-sky-500 italic text-slate-600 dark:text-slate-400">{para.replace('> ', '')}</blockquote>;
                                            }
                                            return <p key={i}>{para}</p>;
                                        })
                                    ) : (
                                        <p className="text-slate-400 italic">Start typing in the "Write" tab to see your formatted preview here.</p>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* Bottom Save & Publish Bar */}
                    <div className="pt-6 border-t border-slate-200/80 dark:border-white/10 flex items-center justify-between">
                        <Link
                            href="/author/dashboard"
                            className="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white"
                        >
                            Cancel
                        </Link>

                        <div className="flex items-center gap-3">
                            <button
                                type="button"
                                disabled={processing}
                                onClick={() => handleSubmit('draft')}
                                className="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition disabled:opacity-50"
                            >
                                Save as Draft
                            </button>

                            <button
                                type="button"
                                disabled={processing}
                                onClick={() => handleSubmit('published')}
                                className="px-6 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-sky-500 to-indigo-600 text-white hover:from-sky-600 hover:to-indigo-700 transition shadow-lg shadow-sky-500/25 disabled:opacity-50"
                            >
                                {processing ? 'Publishing...' : (isEditing && data.status === 'published' ? 'Save & Update' : 'Publish Article Live')}
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </FrontendLayout>
    );
}
