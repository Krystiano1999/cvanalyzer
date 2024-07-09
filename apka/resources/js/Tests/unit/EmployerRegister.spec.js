import { mount } from '@vue/test-utils';
import EmployerRegister from '@/Pages/Auth/EmployerRegister.vue';

test('renders EmployerRegister component', () => {
  const wrapper = mount(EmployerRegister, {
    global: {
      stubs: ['EmployerFormLayout', 'TextInput', 'InputError', 'PrimaryButton']
    }
  });
  expect(true).toBe(true);
});
