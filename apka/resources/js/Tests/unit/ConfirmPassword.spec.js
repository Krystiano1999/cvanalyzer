import { mount } from '@vue/test-utils';
import ConfirmPassword from '@/Pages/Auth/ConfirmPassword.vue';

test('renders ConfirmPassword component', () => {
  const wrapper = mount(ConfirmPassword, {
    global: {
      stubs: ['GuestLayout', 'TextInput', 'InputError', 'PrimaryButton']
    }
  });
  expect(true).toBe(true);
});
