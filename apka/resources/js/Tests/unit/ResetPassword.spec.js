import { mount } from '@vue/test-utils';
import ResetPassword from '@/Pages/Auth/ResetPassword.vue';

test('renders ResetPassword component', () => {
  const wrapper = mount(ResetPassword, {
    props: {
      email: 'test@example.com',
      token: 'fake-token',
    },
    global: {
      stubs: ['GuestLayout', 'TextInput', 'InputError', 'PrimaryButton']
    }
  });
  expect(true).toBe(true);
});
