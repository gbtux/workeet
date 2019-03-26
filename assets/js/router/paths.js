export default [

    {
        path: '*',
        meta: {
            public: true,
        },
        redirect: {
            path: '/404'
        }
    },
    {
        path: '/404',
        meta: {
            public: true,
        },
        name: 'NotFound',
        component: () => import(
            /* webpackChunkName: "routes" */
            `../views/NotFound.vue`
            )
    },
    {
        path: '/403',
        meta: {
            public: true,
        },
        name: 'AccessDenied',
        component: () => import(
            /* webpackChunkName: "routes" */
            `../views/Deny.vue`
            )
    },
    {
        path: '/500',
        meta: {
            public: true,
        },
        name: 'ServerError',
        component: () => import(
            /* webpackChunkName: "routes" */
            `../views/Error.vue`
            )
    },
    {
        path: '/',
        meta: { },
        name: 'Root',
        redirect: {
            name: 'Home'
        }
    },
    {
        path: '/home',
        meta: { breadcrumb: false, chooseTheme: true },
        name: 'Home',
        component: () => import(
            /* webpackChunkName: "routes" */
            `../views/Home.vue`
            )
    }
]
