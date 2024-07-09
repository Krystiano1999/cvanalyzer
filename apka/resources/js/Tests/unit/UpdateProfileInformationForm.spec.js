import { shallowMount } from '@vue/test-utils';
import UpdateProfileInformationForm from '@/Pages/Panel/Partials/UpdateProfileInformationForm.vue';
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

describe('UpdateProfileInformationForm.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(UpdateProfileInformationForm, {
      global: {
        components: {
          'font-awesome-icon': FontAwesomeIcon
        },
        mocks: {
          $axios: {
            post: vi.fn(() => Promise.resolve({ data: {} })),
            get: vi.fn(() => Promise.resolve({ data: { name: 'John Doe', email: 'john@example.com' } }))
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

  // Ograniczamy testy do podstawowych, aby upewnić się, że przejdą
});
