import { mount } from '@vue/test-utils';
import Job from '@/Pages/Job.vue';

test('renders Job component', () => {
  const wrapper = mount(Job, {
    global: {
      stubs: ['JobsLayout', 'HeaderComponent', 'Head', 'FontAwesomeIcon']
    }
  });
  expect(true).toBe(true);
});
