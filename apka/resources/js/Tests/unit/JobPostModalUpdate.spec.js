import { shallowMount } from '@vue/test-utils';
import JobPostModalUpdate from '@/Pages/Panel/Partials/JobPostModalUpdate.vue';
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

describe('JobPostModalUpdate.vue', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = shallowMount(JobPostModalUpdate, {
      global: {
        components: {
          'font-awesome-icon': FontAwesomeIcon
        },
        mocks: {
          $axios: {
            post: vi.fn(() => Promise.resolve({ data: {} }))
          }
        }
      },
      propsData: {
        jobPost: {
          id: 1,
          title: 'Test title',
          description: 'Test description',
          salary_min: 3000,
          salary_max: 5000,
          contract_type: ['UoP'],
          employment_type: ['Etat'],
          experience: ['Junior'],
          job_mode: 'Stacjonarna',
          required_skills: ['Skill1'],
          skills: ['Skill2'],
          foreign_language: ['English']
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
});
