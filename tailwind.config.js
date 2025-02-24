module.exports = {
    content: ["./**/*.php", "./src/**/*.ts"],
    theme: {
      extend: {
        spacing: {
          '82': '328px',
        },
        colors: {
          'main-heading': '#910E1C',
          'primary': '#2E5367',
          'secondary': '#585248',
          'tertiary': '#585248',
          'header-bg': '#DFE8EC',
        },
      },
      fontSize: {
        10:  ['10px', '14px'],
        14:  ['14px', '22px'],
        16: ['16px', '20px'],
        20: ['20px', '24px'],
        26: ['26px', '26px'],
        23: ['23px', '28px'],
        29: ['29px', '29px'],
        '2xl': '1.563rem',
        '3xl': '1.953rem',
        '4xl': '2.441rem',
        '5xl': '3.052rem',
      }
    },
    plugins: [],
  };
  