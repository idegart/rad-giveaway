const path = require('path');

module.exports = {
    output: {
        filename: '[name].js',
        chunkFilename: 'dist/js/chunks/[name].js',
        publicPath: '/',
    },
    resolve: {
        alias: {
            "@js":          path.resolve(__dirname, "./resources/js"),
            "@component":   path.resolve(__dirname, "./resources/js/components"),
            "@layout":      path.resolve(__dirname, "./resources/js/layouts"),
            "@page":        path.resolve(__dirname, "./resources/js/pages"),
            "@plugin":      path.resolve(__dirname, "./resources/js/plugins"),
            "@model":       path.resolve(__dirname, "./resources/js/models"),
            "@sass":        path.resolve(__dirname, "./resources/sass"),
        },
    },
};
