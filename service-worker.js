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
    "revision": "ff54dad213bbb6ba3af71c8ebb1c29bd"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "c01c6febf2dbeb1be831f063ec0b8362"
  },
  {
    "url": "03-trials.html",
    "revision": "8c72f7ae5f485fc5c545c9f5778b9c88"
  },
  {
    "url": "04-charges.html",
    "revision": "4ddd880cef62c6b3cc3f9238a751af9a"
  },
  {
    "url": "05-metered.html",
    "revision": "6b21ff1bb1832ec224fb99259d0ebc5d"
  },
  {
    "url": "06-customer.html",
    "revision": "7374798f87d5c9ae385f1a3584620700"
  },
  {
    "url": "07-invoices.html",
    "revision": "857739f2436aa48b7144ff05ddcf885e"
  },
  {
    "url": "08-refunds.html",
    "revision": "aaddbe058b288e7c35ba7e03bc4a3fab"
  },
  {
    "url": "09-events.html",
    "revision": "78206f17c9f6f9464bb083722f4b1805"
  },
  {
    "url": "10-webhook.html",
    "revision": "f9850e2132dec831c8744ed639c2ddc0"
  },
  {
    "url": "11-testing.html",
    "revision": "781400a3980fe8e68c7dbbe9526d27a5"
  },
  {
    "url": "12-faq.html",
    "revision": "d672e4becd95c30ec99c80984ee3956e"
  },
  {
    "url": "13-upgrade.html",
    "revision": "0773209a2e3124bb2d2971a4b4eb495b"
  },
  {
    "url": "14-retry.html",
    "revision": "ff1617cf797518260faf3c31b046b7e3"
  },
  {
    "url": "15-localization.html",
    "revision": "5f0db225702f404434ad1f638bc56da5"
  },
  {
    "url": "404.html",
    "revision": "74f9f31067026dc163011dfa5e4be772"
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
    "url": "assets/js/2.ca9cb91f.js",
    "revision": "d7c3e399884051d43e13632dfc5745b5"
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
    "url": "assets/js/6.1c3bb5fe.js",
    "revision": "21f1d717be0ab176e93ddd88d5999158"
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
    "url": "assets/js/9.4dbd18aa.js",
    "revision": "5209544b25e2662633221a1e9d6df411"
  },
  {
    "url": "assets/js/app.34a3b78a.js",
    "revision": "d3ce9ef42f3766a30630b265dafe2446"
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
    "revision": "9aa34cb9138b03187f323bfbf8444be0"
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
