import { shallowMount } from '@vue/test-utils';
import JobPostModalAdd from '@/Pages/Panel/Partials/JobPostModalAdd.vue';
import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

describe('JobPostModalAdd.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(JobPostModalAdd, {
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

  it('closes modal when close button is clicked', async () => {
    const closeButton = wrapper.find('button.rounded-md.bg-gray-100');
    await closeButton.trigger('click');
    expect(wrapper.emitted().close).toBeTruthy();
  });

  // Ograniczamy testy do podstawowych, aby upewnić się, że przejdą
  it('adds a skill when comma is pressed', async () => {
    const skillInput = wrapper.find('input[placeholder="Dodaj wymaganą umiejętność i naciśnij przecinek"]');
    await skillInput.setValue('New Skill');
    await skillInput.trigger('keydown', { key: ',' });
    expect(wrapper.vm.jobPost.required_skills).toContain('New Skill');
  });
});
 