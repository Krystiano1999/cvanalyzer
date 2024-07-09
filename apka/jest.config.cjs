export default {
  moduleFileExtensions: ['js', 'json', 'vue'],
  transform: {
    '^.+\\.vue$': '@vue/vue3-jest',
    '^.+\\.js$': 'babel-jest',
  },
  testMatch: ['**/resources/js/Tests/unit/**/*.spec.(js|jsx|ts|tsx)'],
  globals: {
    'vue-jest': {
      pug: { doctype: 'html' },
      templateCompiler: require('@vue/compiler-sfc'),
    },
  },
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/resources/js/$1',
  },
  testEnvironment: 'jest-environment-jsdom',
};
