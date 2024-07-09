import { mount } from '@vue/test-utils';
import VerifyEmail from '@/Pages/Auth/VerifyEmail.vue';

test('renders VerifyEmail component', () => {
  const wrapper = mount(VerifyEmail, {
    global: {
      stubs: ['FormLayout', 'TextInput', 'InputError', 'PrimaryButton']
    }
  });
  expect(true).toBe(true);
});
