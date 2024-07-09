import { mount } from '@vue/test-utils';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';

describe('UpdatePasswordForm', () => {
  it('renders component correctly', () => {
    const wrapper = mount(UpdatePasswordForm);
    expect(wrapper.exists()).toBe(true);
  });
});
