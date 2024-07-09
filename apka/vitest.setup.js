import axios from 'axios';
import { config } from '@vue/test-utils';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { vi } from 'vitest';
import { InertiaHead, Link, usePage, Head } from '@inertiajs/vue3';

// Mockowanie axios
vi.mock('axios');

axios.post.mockResolvedValue({ data: {} });
axios.get.mockResolvedValue({ data: {} });

// Konfiguracja globalnych komponentów
config.global.components = {
    'font-awesome-icon': FontAwesomeIcon
};

// Mockowanie Inertia i globalne zmienne
global.$page = {
    props: {
        auth: {
            user: null,
        },
        errors: {}
    },
    component: '',
    url: '',
    version: '',
    scrollRegions: [],
    rememberedState: null,
    resolvedErrors: {}
};

global.route = vi.fn().mockImplementation((name) => {
    return {
        current: (route) => route === name,
    };
});
global.InertiaHead = InertiaHead;
global.Link = Link;
global.Head = Head;

// Mockowanie Inertia
vi.mock('@inertiajs/vue3', () => ({
    InertiaHead: global.InertiaHead,
    usePage: () => global.$page,
    useForm: () => ({
        errors: {},
        post: vi.fn(),
    }),
    Link: global.Link,
    Head: global.Head,
    defineProps: () => ({}),
    defineEmits: () => ({}),
}));
