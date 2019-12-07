module.exports = {
    title: 'Lararole',
    description: 'Role Management with modules and other features',
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
            {
                title: 'Tutorial',
                collapsable: false,
                sidebarDepth: 3,
                children: [
                    '/tutorial/',
                ]
            },
        ]
    }
};
