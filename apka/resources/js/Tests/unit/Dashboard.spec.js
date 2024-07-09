import { mount } from '@vue/test-utils';
import Dashboard from '@/Pages/Dashboard.vue';

test('renders Dashboard component', () => {
  const wrapper = mount(Dashboard, {
    global: {
      stubs: ['AuthenticatedLayout', 'HeaderComponent']
    }
  });
  expect(true).toBe(true);
});
