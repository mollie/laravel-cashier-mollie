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
    "revision": "5e02a9a0f9322ebef76fb757fc221bf9"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "0101306bdc06757d1d84c020a70d1d5e"
  },
  {
    "url": "03-trials.html",
    "revision": "cf67282e191cd4eec911dc109afacd7f"
  },
  {
    "url": "04-charges.html",
    "revision": "e19eeae214ba47144d65380ff9b87fb7"
  },
  {
    "url": "05-metered.html",
    "revision": "ec7c8044d89e8e3ca578097782bb89e8"
  },
  {
    "url": "06-customer.html",
    "revision": "761bebcb680d195097700d23b2408bb5"
  },
  {
    "url": "07-invoices.html",
    "revision": "a960adb5f32506a8bc2e10c76cf8aa64"
  },
  {
    "url": "08-refunds.html",
    "revision": "e7ebbd56bbfc9889b6b2c71699ffb7ea"
  },
  {
    "url": "09-events.html",
    "revision": "65ea7f580709183b23279537f13bf364"
  },
  {
    "url": "10-webhook.html",
    "revision": "0e8e341e0847b6f958134e07e61f7071"
  },
  {
    "url": "11-testing.html",
    "revision": "6ae4da5b076ebac3b4a17346bca6ce08"
  },
  {
    "url": "12-faq.html",
    "revision": "22d7adc3baf68c23bed2c488dd1fd350"
  },
  {
    "url": "13-upgrade.html",
    "revision": "43b3bf591d82110c229d709ea785de0d"
  },
  {
    "url": "14-retry.html",
    "revision": "1de7e2263e8026837052680775f38486"
  },
  {
    "url": "15-localization.html",
    "revision": "64303c5ab40d78812e76f8a252b4565c"
  },
  {
    "url": "404.html",
    "revision": "c9d84dc3ba2836a0771bc6adbd37a006"
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
    "url": "assets/css/0.styles.8d363c57.css",
    "revision": "86b0fd77cc538defc1d3f70fc50d22c8"
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
    "url": "assets/js/10.10907cbf.js",
    "revision": "88118981a832fae0c1e6dbae10ee9bc7"
  },
  {
    "url": "assets/js/11.06d48f1a.js",
    "revision": "56edffcebfa5b6b861f2f0f7f4431377"
  },
  {
    "url": "assets/js/12.b1b4f26a.js",
    "revision": "dd1e4763d4339d246784a8b2346fb870"
  },
  {
    "url": "assets/js/13.a368191a.js",
    "revision": "42dec482bda19de54456c3447d52f6e3"
  },
  {
    "url": "assets/js/14.cc8e3b38.js",
    "revision": "fb651838ab400c4c58b10d5512fc1872"
  },
  {
    "url": "assets/js/15.e5b7484e.js",
    "revision": "40aed53ce60cbae8f13430164339cc42"
  },
  {
    "url": "assets/js/16.1699dcd5.js",
    "revision": "411fa84db7a07aa1f211e601f5a286c8"
  },
  {
    "url": "assets/js/17.0203c377.js",
    "revision": "8714daf39855b8f03ace53c78f5b84f5"
  },
  {
    "url": "assets/js/18.fca08ff7.js",
    "revision": "d27d0f7be67fb52b371c541b54bf926c"
  },
  {
    "url": "assets/js/19.b40fc194.js",
    "revision": "8f47743734698feb5da6f5547629e510"
  },
  {
    "url": "assets/js/2.9a92ed29.js",
    "revision": "d6bb4eabe15de3cb24ed399fd18623ae"
  },
  {
    "url": "assets/js/20.21920152.js",
    "revision": "a117e57a0be77a21836101be0de5840a"
  },
  {
    "url": "assets/js/21.852c7e44.js",
    "revision": "34bbc79ca5953152debe5e9d512efc28"
  },
  {
    "url": "assets/js/22.1cd496ae.js",
    "revision": "5260b3d44ef811f7ea8271efaee423cc"
  },
  {
    "url": "assets/js/23.9033ca21.js",
    "revision": "c5917c2ac2012ffc04764ef4bf33bbfb"
  },
  {
    "url": "assets/js/24.e4940cdf.js",
    "revision": "1af3a2e2a12c6a066cf4bf3baa87649c"
  },
  {
    "url": "assets/js/3.ef87c80f.js",
    "revision": "79a7f9f0eca783e7b2b7bdf6a1179e6e"
  },
  {
    "url": "assets/js/4.c942773d.js",
    "revision": "7a8fd493b7b3dec709ccc7ff1a017202"
  },
  {
    "url": "assets/js/5.d52db440.js",
    "revision": "020bc69a323754abf041eeca903594f8"
  },
  {
    "url": "assets/js/6.d94c8d48.js",
    "revision": "86025134757914bca6638500df08cbb9"
  },
  {
    "url": "assets/js/7.ed75cf2c.js",
    "revision": "dcc2dc964fa49429b1218ae9de1b1f1d"
  },
  {
    "url": "assets/js/8.3bbd489b.js",
    "revision": "a64d15335a4f22a11b703358ca59369e"
  },
  {
    "url": "assets/js/9.e116eba4.js",
    "revision": "1ba6c11c8dd55e5d36870774065d5c42"
  },
  {
    "url": "assets/js/app.3b7c6778.js",
    "revision": "035548e5aa10fd3370d50380b658e1df"
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
    "revision": "d2be7c02df4f46ce34e155af5feadf0c"
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
