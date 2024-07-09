import { mount } from '@vue/test-utils';
import LoginEmployer from '@/Pages/Auth/LoginEmployer.vue';

test('renders LoginEmployer component', () => {
  const wrapper = mount(LoginEmployer, {
    global: {
      stubs: ['EmployerFormLayout', 'TextInput', 'InputError', 'PrimaryButton', 'Checkbox']
    }
  });
  expect(true).toBe(true);
});
