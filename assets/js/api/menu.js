const Menu =  [
        { header: 'Apps' },
        {
            title: 'Mon Espace',
            group: 'apps',
            icon: 'home',
            name: 'Home',
        },
        {
            title: 'Fichiers partagés',
            group: 'apps',
            icon: 'share',
            name: 'Shared',
        },
        {
            title: 'Contacts',
            group: 'apps',
            icon: 'people',
            name: 'Contacts',
        }
    ]

// reorder menu
Menu.forEach((item) => {
    if (item.items) {
        item.items.sort((x, y) => {
            let textA = x.title.toUpperCase();
            let textB = y.title.toUpperCase();
            return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
        });
    }
});

export default Menu;