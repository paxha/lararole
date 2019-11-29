module.exports = {
    title: 'LaraRole',
    description: 'Laravel Role Management with modules and other features',
    base: '/',
    home: true,
    themeConfig: {
        logo: '/images/logo.png',
        nav: [
            {text: 'Home', link: '/'},
            {text: 'Guide', link: '/guide/'},
            {text: 'Development', link: '/development/role.md'},
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
                    '/guide/database_structure.md',
                    '/guide/configuration.md',
                    '/guide/commands.md',
                    '/guide/views_directory.md',
                ]
            },
            {
                title: 'Development',
                collapsable: false,
                sidebarDepth: 2,
                children: [
                    '/development/role.md',
                    '/development/user.md',
                ]
            }
        ]
    }
};
