module.exports = {
    devServer: {
        proxy: "http://secretcode.test"
    },
    outputDir: "../../../public/assets/app",
    publicPath: process.env.NODE_ENV === "production" ? "/assets/app/" : "/",
    indexPath:
        process.env.NODE_ENV === "production"
            ? "../../../resources/views/app.blade.php"
            : "index.html",
    transpileDependencies: ["vuetify"],
    chainWebpack: config => {
        config.plugin("html").tap(args => {
            args[0].title = "Secretcode";
            args[0].meta = {
                csfr: "{{ csrf-token }}"
            };

            return args;
        });
    }
};
