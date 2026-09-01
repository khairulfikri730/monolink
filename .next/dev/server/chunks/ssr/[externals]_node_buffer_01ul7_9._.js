module.exports = [
"[externals]/node:buffer [external] (node:buffer, cjs, async loader)", ((__turbopack_context__) => {

__turbopack_context__.v((parentImport) => {
    return Promise.all([
  "server/chunks/ssr/[externals]_node_buffer_00a6lhv._.js"
].map((chunk) => __turbopack_context__.l(chunk))).then(() => {
        return parentImport("[externals]/node:buffer [external] (node:buffer, cjs)");
    });
});
}),
];