module.exports = {
    title: 'Laravel Cashier Mollie v2',
    description: 'Laravel Cashier provides an expressive, fluent interface to subscriptions using Mollie\'s billing services.',
    head: [
        ['link', { rel: "apple-touch-icon", sizes: "180x180", href: "/apple-touch-icon.png"}],
        ['link', { rel: "icon", type: "image/png", sizes: "32x32", href: "/favicon-32x32.png"}],
        ['link', { rel: "icon", type: "image/png", sizes: "16x16", href: "/favicon-16x16.png"}],
        ['link', { rel: "manifest", href: "/manifest.json"}],
        ['meta', { name: "theme-color", content: "#ffffff"}],
        ['meta', { name: "viewport", content: "width=device-width"}],
        ['script', { src: "https://cdn.usefathom.com/script.js", spa: "auto", site: "ANMLOYPH", defer:true}]

    ],
    themeConfig: {
        logo: '/assets/img/cashier-mollie.svg',
        repo: 'mollie/laravel-cashier-mollie',
        authors: [
            {
                'name': 'Mollie.com',
                'email': 'support@mollie.com',
                'homepage': 'https://www.mollie.com',
                'role': 'Owner'
            },
            {
                'name': 'Sander van Hooft',
                'email': 'info@sandervanhooft.com',
                'homepage': 'https://www.sandervanhooft.com',
                'role': 'Developer'
            }
        ],
        docsDir: 'docs',
        docsBranch: 'main',
        editLinks: true,
        editLinkText: 'Improve this page (submit a PR)',
        domain: 'https://www.cashiermollie.com',
        displayAllHeaders: true,
        sidebar: [
            ['/', 'Introduction'],
            '/01-installation',
            '/13-upgrade',
            '/16-configuration',
            '/02-subscriptions',
            '/03-trials',
            '/04-charges',
            '/05-metered',
            '/06-customer',
            '/07-invoices',
            '/08-refunds',
            '/14-retry',
            '/09-events',
            '/15-localization',
            '/10-webhook',
            '/11-testing',
            '/12-faq',
            //'/13-upgrade',
        ]
    },
    base: '/',
    plugins: [
        ['seo', {
            siteTitle: (_, $site) => $site.title,
            title: $page => $page.title,
            description: $page => $page.frontmatter.description,
            author: (_, $site) => $site.themeConfig.authors[0],
            tags: $page => $page.frontmatter.tags,
            twitterCard: _ => 'summary_large_image',
            type: $page => 'website',
            url: (_, $site, path) => ($site.themeConfig.domain || '') + path,
            image: ($page, $site) => "https://www.cashiermollie.com/assets/pages/laravelcashiermollie.jpg",
            publishedAt: $page => $page.frontmatter.date && new Date($page.frontmatter.date),
            modifiedAt: $page => $page.lastUpdated && new Date($page.lastUpdated),
        }],
        '@vuepress/last-updated',
        ['@vuepress/pwa', {
            serviceWorker: true,
            updatePopup: true
        }],
        [
            "@mr-hope/sitemap",
            {
                hostname: "https://www.cashiermollie.com"
            },
        ],
    ]
}
