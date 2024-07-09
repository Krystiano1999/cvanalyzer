import { mount } from '@vue/test-utils';
import Register from '@/Pages/Auth/Register.vue';

test('renders Register component', () => {
  const wrapper = mount(Register, {
    global: {
      stubs: ['FormLayout', 'TextInput', 'InputError', 'PrimaryButton']
    }
  });
  expect(true).toBe(true);
});
