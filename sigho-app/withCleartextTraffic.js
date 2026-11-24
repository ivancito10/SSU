const { withAndroidManifest } = require('expo/config-plugins');

/**
 * Plugin de Expo para habilitar conexiones HTTP en red local (android:usesCleartextTraffic="true")
 * indispensable para que el APK instalado pueda conectarse al servidor NestJS por IP (ej. http://192.168.2.151:3000/api).
 */
module.exports = function withCleartextTraffic(config) {
  return withAndroidManifest(config, async (config) => {
    const androidManifest = config.modResults;
    const mainApplication = androidManifest.manifest?.application?.[0];

    if (mainApplication) {
      mainApplication.$['android:usesCleartextTraffic'] = 'true';
    }

    return config;
  });
};
