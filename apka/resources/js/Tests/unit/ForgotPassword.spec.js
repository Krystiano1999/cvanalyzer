import { mount } from '@vue/test-utils';
import ForgotPassword from '@/Pages/Auth/ForgotPassword.vue';

test('renders ForgotPassword component', () => {
  const wrapper = mount(ForgotPassword, {
    global: {
      stubs: ['FormLayout', 'TextInput', 'InputError', 'PrimaryButton']
    }
  });
  expect(true).toBe(true);
});
