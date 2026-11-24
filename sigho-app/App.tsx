import { StatusBar } from 'expo-status-bar';
import { StyleSheet } from 'react-native';
import React from 'react';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { AppNavigator } from './src/core/navigation/AppNavigator';
import { AlertaGlobalModal } from './src/core/components/AlertaGlobalModal';
import { InstaladorPwaWeb } from './src/core/components/InstaladorPwaWeb';

export default function App() {
  return (
    <SafeAreaProvider>
      <AppNavigator />
      <AlertaGlobalModal />
      <InstaladorPwaWeb />
    </SafeAreaProvider>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#ffffffff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
