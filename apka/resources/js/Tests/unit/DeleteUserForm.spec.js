import { mount } from '@vue/test-utils';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';

describe('DeleteUserForm', () => {
  it('renders component correctly', () => {
    const wrapper = mount(DeleteUserForm);
    expect(wrapper.exists()).toBe(true);
  });
});
