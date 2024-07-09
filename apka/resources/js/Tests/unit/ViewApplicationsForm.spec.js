import { shallowMount } from '@vue/test-utils';
import ViewApplicationsForm from '@/Pages/Panel/Partials/ViewApplicationsForm.vue';
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

describe('ViewApplicationsForm.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(ViewApplicationsForm, {
      global: {
        components: {
          'font-awesome-icon': FontAwesomeIcon
        },
        mocks: {
          $axios: {
            post: vi.fn(() => Promise.resolve({ data: {} }))
          }
        }
      }
    });
  });

  afterEach(() => {
    wrapper.unmount();
  });

  it('renders component correctly', () => {
    expect(wrapper.exists()).toBe(true);
  });

  // Add more tests as needed for other functionalities
});
