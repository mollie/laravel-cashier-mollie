/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "01-installation.html",
    "revision": "f9034fcf977e4d7a1228244a66006d8d"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "4bf768e979f246b60bbb5546ae8de047"
  },
  {
    "url": "03-trials.html",
    "revision": "571bccb9de857e4eb7b318a15bebb059"
  },
  {
    "url": "04-charges.html",
    "revision": "6529ff964b81edfcc60e33b1a2d40e09"
  },
  {
    "url": "05-metered.html",
    "revision": "385e578d24e6117cb0a446c0487f5406"
  },
  {
    "url": "06-customer.html",
    "revision": "dc80017d2952c47e6afc731ee5029619"
  },
  {
    "url": "07-invoices.html",
    "revision": "f9f88fc2a2ece21d135bb290bc58ecc7"
  },
  {
    "url": "08-refunds.html",
    "revision": "c8c2ec1323e2062764bac383f03bd368"
  },
  {
    "url": "09-events.html",
    "revision": "c8ce6e5b2f6c6f20a65b3a41e505b147"
  },
  {
    "url": "10-webhook.html",
    "revision": "c3c61f1ecbaeb0d89e34f6c5e1f9a46b"
  },
  {
    "url": "11-testing.html",
    "revision": "07a2de165cc6ac57d3805a67d9e1b464"
  },
  {
    "url": "12-faq.html",
    "revision": "876e0438cc31965aac8a8002315643f1"
  },
  {
    "url": "13-upgrade.html",
    "revision": "82eb99290a612dd603f41ac78a4e8000"
  },
  {
    "url": "14-retry.html",
    "revision": "dc62125b3e2cb673240f589f321386ca"
  },
  {
    "url": "15-localization.html",
    "revision": "c60d646e367cc8606c4054ff27e0ba2f"
  },
  {
    "url": "16-configuration.html",
    "revision": "8070ef528305d84cdc2dc0fc06e13930"
  },
  {
    "url": "404.html",
    "revision": "557eda035e6324a6f9865a6d8dae695d"
  },
  {
    "url": "android-chrome-192x192.png",
    "revision": "7f1890f254594de8c4b514dee90ed629"
  },
  {
    "url": "android-chrome-384x384.png",
    "revision": "bc31b03048d4a3ba4fe82ce2389b0b38"
  },
  {
    "url": "apple-touch-icon.png",
    "revision": "19f3e3722c9d450ecfa73e8a92aaa47a"
  },
  {
    "url": "assets/css/0.styles.359cd878.css",
    "revision": "d2acd007420eb02d9daa36f53e706e3e"
  },
  {
    "url": "assets/img/cashier-mollie.svg",
    "revision": "06f0ab467b31062098cffb5ff5d18bc6"
  },
  {
    "url": "assets/img/laravelcashiermollie.a7bde0e4.jpg",
    "revision": "a7bde0e4173f90acd2d72e0eb69d2764"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/1.f15cd340.js",
    "revision": "b0319c435d08d9c77906b4cf300cffa0"
  },
  {
    "url": "assets/js/10.a530d526.js",
    "revision": "4a08f17b8721665ca733c821566e2740"
  },
  {
    "url": "assets/js/11.1e7c7bac.js",
    "revision": "80baaf9dbc219a0851195f8768e9a120"
  },
  {
    "url": "assets/js/12.938990d7.js",
    "revision": "9b360116d677fd3adea2107ee8551c38"
  },
  {
    "url": "assets/js/13.2a1084cb.js",
    "revision": "4d4e1f567ad90f098a458b54e4bd3ec3"
  },
  {
    "url": "assets/js/14.cdcede19.js",
    "revision": "bd3ceb5f6b290f0f508da08f235cbc31"
  },
  {
    "url": "assets/js/15.3a201ea9.js",
    "revision": "c77537b3a8ea48cd900e6dbb26af66b0"
  },
  {
    "url": "assets/js/16.439c238f.js",
    "revision": "3361cc3dafb2d4b817750d8ee5f911d9"
  },
  {
    "url": "assets/js/17.df89d07c.js",
    "revision": "9002e022fa31b499982e56acff44c18a"
  },
  {
    "url": "assets/js/18.73c4f12a.js",
    "revision": "6ab23bcf9b67ea205c68abda155b1510"
  },
  {
    "url": "assets/js/19.7b2d8a2d.js",
    "revision": "b9e4e7f586d3298bbe406a111cba91cb"
  },
  {
    "url": "assets/js/2.f26ec0ac.js",
    "revision": "b92800184ecb1535a3778c8aa6f72f4e"
  },
  {
    "url": "assets/js/20.aeaa4172.js",
    "revision": "588131b8a44047f6dfc09413f713487c"
  },
  {
    "url": "assets/js/21.fcdc9ff3.js",
    "revision": "761d728f2494bb7d0ade47bdda82a8bb"
  },
  {
    "url": "assets/js/22.ef03ce04.js",
    "revision": "8e0ad9fb24cb8212d4976ed24ae851a6"
  },
  {
    "url": "assets/js/23.6f5b85e5.js",
    "revision": "3a01e64126a04ce399d8b3407d44625e"
  },
  {
    "url": "assets/js/24.9b44673a.js",
    "revision": "4185d1f231cbdb42e21d3622e1f613c5"
  },
  {
    "url": "assets/js/25.74ce4607.js",
    "revision": "9bf2e93c466faa4df0184ce689319e48"
  },
  {
    "url": "assets/js/26.d668111d.js",
    "revision": "bbeab97e05de36bc21e04863feeeb6d7"
  },
  {
    "url": "assets/js/27.91db9730.js",
    "revision": "bfce56c0721fa54e16332c06734ecd22"
  },
  {
    "url": "assets/js/28.0017c029.js",
    "revision": "f60ce24ac8f4c4b83e69efa2e6cde8d6"
  },
  {
    "url": "assets/js/29.2ccec40a.js",
    "revision": "4c7192a1783df77f4f0ee8b9317fe4c3"
  },
  {
    "url": "assets/js/3.5475d080.js",
    "revision": "644ed254eadd3042b0e6d867b3791e7e"
  },
  {
    "url": "assets/js/30.36e8ea24.js",
    "revision": "df0503af289ca95e54b3f38fcac1ff22"
  },
  {
    "url": "assets/js/31.415577f3.js",
    "revision": "100dcfddc1e04ec3688db186b265a352"
  },
  {
    "url": "assets/js/32.f6641fc0.js",
    "revision": "ca07881e8a08778d1d44719d0c199ed6"
  },
  {
    "url": "assets/js/33.bcd7dc2a.js",
    "revision": "334b1f46e5a3bb64dfb0e0ef58e946d1"
  },
  {
    "url": "assets/js/34.e63ead20.js",
    "revision": "3af37ecf6cc676d8ca895e929b97c709"
  },
  {
    "url": "assets/js/35.8463f178.js",
    "revision": "769f214ce979165e2bc37410b39c80b0"
  },
  {
    "url": "assets/js/36.58cafeb4.js",
    "revision": "4c052614c2fbea072a5f6fafa8b9ea60"
  },
  {
    "url": "assets/js/37.4234d260.js",
    "revision": "42eee4addc3137a957fdd6be6d1ff0b8"
  },
  {
    "url": "assets/js/38.f5e07fbf.js",
    "revision": "709a4ffd24a084dfed3d4476a642848d"
  },
  {
    "url": "assets/js/39.17731b20.js",
    "revision": "ec6eac0d34e008588d69a0225d9abf51"
  },
  {
    "url": "assets/js/4.2a49da8b.js",
    "revision": "411f783af3e4fae42e7b41539de36b80"
  },
  {
    "url": "assets/js/40.d89560ff.js",
    "revision": "bf82a21496f8ae170121784199fddd8e"
  },
  {
    "url": "assets/js/5.c4495a60.js",
    "revision": "38474fe2a849d0e5209180885a27259e"
  },
  {
    "url": "assets/js/6.9b2ca02e.js",
    "revision": "2cbf246bcd7093c87690cd170ab6538f"
  },
  {
    "url": "assets/js/7.ac23c900.js",
    "revision": "420b57ba6ac9a737d5407b949554ede9"
  },
  {
    "url": "assets/js/app.fc6ce554.js",
    "revision": "5fb9934ec7dc6fe5dda735af0a6b4551"
  },
  {
    "url": "assets/js/vendors~docsearch.6c159022.js",
    "revision": "411f22d9279082036324329298ea14c6"
  },
  {
    "url": "assets/pages/laravelcashiermollie.jpg",
    "revision": "a7bde0e4173f90acd2d72e0eb69d2764"
  },
  {
    "url": "favicon-16x16.png",
    "revision": "e8cead60a31ba0059df44368227bba35"
  },
  {
    "url": "favicon-32x32.png",
    "revision": "2f21759d559a5e952851228adbb628ec"
  },
  {
    "url": "index.html",
    "revision": "b2d73c9608c5408fd8dd97e9664f84cb"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
