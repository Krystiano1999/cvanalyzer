import { shallowMount } from '@vue/test-utils';
import CVAnalysisForm from '@/Pages/Panel/Partials/CVAnalysisForm.vue';
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

describe('CVAnalysisForm.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(CVAnalysisForm, {
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
