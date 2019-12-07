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
            /*{text: 'How to use?', link: '/how_to_use/'},*/
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
                    '/guide/using_lararole.md',
                ]
            },
            /*{
                title: 'How to use?',
                collapsable: false,
                sidebarDepth: 3,
                children: [
                    '/how_to_use/',
                ]
            }*/
        ]
    }
};
