module.exports = {
    devServer: {
        proxy: "http://secretcode.test"
    },
    outputDir: "../../../public/assets/app",
    publicPath: "/",
    indexPath: "index.html",
    transpileDependencies: ["vuetify"]
};
