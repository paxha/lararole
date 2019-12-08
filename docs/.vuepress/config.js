module.exports = {
    title: 'Lararole',
    description: 'Lararole is a Laravel library, provides Role Management with permissions. Basically this library provides a basic structure of application and instructions to use it. Using this manageable structure you can build large and robust applications.',
    author: 'Hassan Raza Pasha',
    base: '/lararole/',
    home: true,
    plugins: {
        'seo': {
            siteTitle: (_, $site) => $site.title,
            title: $page => $page.title,
            description: $page => $page.frontmatter.description,
            author: (_, $site) => $site.author,
            tags: $page => $page.frontmatter.tags,
            twitterCard: _ => 'Laravel role management library, Laravel role management package',
            type: $page => ['articles', 'posts', 'blog'].some(folder => $page.regularPath.startsWith('/' + folder)) ? 'article' : 'website',
            url: (_, $site, path) => ($site.themeConfig.domain || '') + path,
            image: ($page, $site) => $page.frontmatter.image && (($site.themeConfig.domain || '') + $page.frontmatter.image),
            publishedAt: $page => $page.frontmatter.date && new Date($page.frontmatter.date),
            modifiedAt: $page => $page.lastUpdated && new Date($page.lastUpdated),
        }
    },
    themeConfig: {
        logo: '/images/logo.png',
        nav: [
            {text: 'Home', link: '/'},
            {text: 'Guide', link: '/guide/'},
            {text: 'How to use', link: '/how_to_use/'},
            {text: 'Tutorial', link: '/tutorial/'},
            {text: 'GitHub', link: 'https://github.com/paxha/lararole'}
        ],
        sidebar: [
            {
                title: 'Guide',
                collapsable: false,
                sidebarDepth: 3,
                children: [
                    '/guide/',
                    '/guide/getting_started.md',
                    '/guide/configuration.md',
                ]
            },
            {
                title: 'How to use',
                collapsable: false,
                sidebarDepth: 3,
                children: [
                    '/how_to_use/',
                    '/how_to_use/module.md',
                    '/how_to_use/role.md',
                ]
            },
            // {
            //     title: 'Tutorial',
            //     collapsable: false,
            //     sidebarDepth: 3,
            //     children: [
            //         '/tutorial/',
            //     ]
            // },
        ]
    }
};
