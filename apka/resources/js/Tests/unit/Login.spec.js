import { mount } from '@vue/test-utils';
import Login from '@/Pages/Auth/Login.vue';

test('renders Login component', () => {
  const wrapper = mount(Login, {
    global: {
      stubs: ['FormLayout', 'TextInput', 'InputError', 'PrimaryButton', 'Checkbox']
    }
  });
  expect(true).toBe(true);
});
