module.exports = {
    title: 'LaraRole',
    description: 'Laravel Role Management with modules and other features',
    base: '/',
    home: true,
    plugins: [
        [
            '@vuepress/google-analytics',
            {
                'ga': 'UA-153614881-1' // UA-00000000-0
            }
        ]
    ],
    themeConfig: {
        logo: '/images/logo.png',
        nav: [
            {text: 'Home', link: '/'},
            {text: 'Guide', link: '/guide/'},
            {text: 'Development', link: '/how_to_use/'},
            {text: 'GitHub', link: 'https://github.com/paxha/lararole'}
        ],
        sidebar: [
            {
                title: 'Guide',
                collapsable: false,
                sidebarDepth: 2,
                children: [
                    '/guide/',
                    '/guide/getting_started.md',
                    '/guide/how_it_works.md',
                ]
            },
            {
                title: 'Development',
                collapsable: false,
                sidebarDepth: 2,
                children: [
                    '/how_to_use/',
                ]
            }
        ]
    }
};
