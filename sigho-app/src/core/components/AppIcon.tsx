import React from 'react';
import { View, Text, Platform } from 'react-native';

export type NombreIcono =
    | 'bell'
    | 'logout'
    | 'menu'
    | 'home'
    | 'user'
    | 'users'
    | 'shield'
    | 'headset'
    | 'wrench'
    | 'laptop'
    | 'gear'
    | 'globe'
    | 'pin'
    | 'location'
    | 'calendar'
    | 'clock'
    | 'play'
    | 'check'
    | 'refresh'
    | 'alert'
    | 'search'
    | 'plus'
    | 'clipboard'
    | 'folder'
    | 'document'
    | 'document-text'
    | 'camera'
    | 'trash'
    | 'lock'
    | 'id-card'
    | 'close'
    | 'arrow-left'
    | 'arrow-right'
    | 'face-id'
    | 'fingerprint';

interface AppIconProps {
    name: NombreIcono;
    size?: number;
    color?: string;
}

/**
 * Iconos vectoriales limpios (0% emoticones/emojis) compatibles con Android, iOS y Web,
 * funcionando 100% sin conexión a internet y sin depender de fuentes externas.
 */
export const AppIcon = ({ name, size = 18, color = '#164a96' }: AppIconProps) => {
    if (Platform.OS === 'web') {
        const s = size;
        switch (name) {
            case 'bell':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                );
            case 'logout':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.1" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                );
            case 'menu':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                );
            case 'home':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                );
            case 'user':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                );
            case 'users':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                );
            case 'shield':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                );
            case 'headset':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" />
                    </svg>
                );
            case 'wrench':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                );
            case 'laptop':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                        <line x1="8" y1="21" x2="16" y2="21" />
                        <line x1="12" y1="17" x2="12" y2="21" />
                    </svg>
                );
            case 'gear':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                    </svg>
                );
            case 'globe':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="2" y1="12" x2="22" y2="12" />
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                );
            case 'pin':
            case 'location':
            case 'location':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                );
            case 'calendar':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                );
            case 'clock':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                );
            case 'play':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3" />
                    </svg>
                );
            case 'check':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.3" strokeLinecap="round" strokeLinejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                );
            case 'refresh':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <polyline points="23 4 23 10 17 10" />
                        <polyline points="1 20 1 14 7 14" />
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                    </svg>
                );
            case 'alert':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                );
            case 'search':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                );
            case 'plus':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                );
            case 'clipboard':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                    </svg>
                );
            case 'folder':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                    </svg>
                );
            case 'document':
            case 'document-text':
            case 'document-text':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                    </svg>
                );
            case 'camera':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                        <circle cx="12" cy="13" r="4" />
                    </svg>
                );
            case 'trash':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                );
            case 'lock':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                );
            case 'id-card':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                    </svg>
                );
            case 'close':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                );
            case 'arrow-left':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12" />
                        <polyline points="12 19 5 12 12 5" />
                    </svg>
                );
            case 'face-id':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M4 8V6a2 2 0 0 1 2-2h2" />
                        <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                        <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                        <path d="M16 20h2a2 2 0 0 0 2-2v-2" />
                        <line x1="9" y1="9" x2="9" y2="11" />
                        <line x1="15" y1="9" x2="15" y2="11" />
                        <path d="M12 9v4h-1" />
                        <path d="M9 15.5c.8.8 1.9 1.2 3 1.2s2.2-.4 3-1.2" />
                    </svg>
                );
            case 'fingerprint':
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="1.9" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M2 12C2 6.5 6.5 2 12 2a10 10 0 0 1 8 4" />
                        <path d="M5 19.5C5.5 18 6 15 6 12a6 6 0 0 1 .34-2" />
                        <path d="M8.65 22c.21-.66.45-1.32.57-2" />
                        <path d="M9 6.8a6 6 0 0 1 9 5.2v2" />
                        <path d="M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4" />
                        <path d="M14 13.12c0 2.38 0 6.38-1 8.88" />
                        <path d="M17.29 21.02c.12-.6.43-2.3.5-3.02" />
                        <path d="M2 16h.01" />
                        <path d="M21.8 16c.2-2 .131-5.354 0-6" />
                        <path d="M5 19.5c.5-1.5 1-4.5 1-7.5" />
                    </svg>
                );
            case 'arrow-right':
            default:
                return (
                    <svg width={s} height={s} viewBox="0 0 24 24" fill="none" stroke={color} strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                );
        }
    }

    // =========================================================================
    // ANDROID / iOS: TRAZOS GEOMÉTRICOS NATIVOS (0% EMOJIS, 100% VECTORIAL)
    // =========================================================================
    const box = { width: size, height: size, justifyContent: 'center' as const, alignItems: 'center' as const };
    const bw = Math.max(1.5, size * 0.095);

    switch (name) {
        case 'menu':
            return (
                <View style={[box, { justifyContent: 'space-around', paddingVertical: size * 0.18 }]}>
                    <View style={{ width: size * 0.78, height: bw, backgroundColor: color, borderRadius: 1 }} />
                    <View style={{ width: size * 0.78, height: bw, backgroundColor: color, borderRadius: 1 }} />
                    <View style={{ width: size * 0.78, height: bw, backgroundColor: color, borderRadius: 1 }} />
                </View>
            );

        case 'bell':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.62,
                            height: size * 0.56,
                            borderTopLeftRadius: size * 0.31,
                            borderTopRightRadius: size * 0.31,
                            borderWidth: bw,
                            borderColor: color,
                        }}
                    />
                    <View style={{ width: size * 0.78, height: bw, backgroundColor: color, borderRadius: 1, marginTop: -1 }} />
                    <View
                        style={{
                            width: size * 0.24,
                            height: size * 0.14,
                            borderBottomLeftRadius: size * 0.12,
                            borderBottomRightRadius: size * 0.12,
                            backgroundColor: color,
                            marginTop: 1,
                        }}
                    />
                </View>
            );

        case 'logout':
            return (
                <View style={[box, { flexDirection: 'row' }]}>
                    <View
                        style={{
                            width: size * 0.45,
                            height: size * 0.72,
                            borderTopWidth: bw,
                            borderLeftWidth: bw,
                            borderBottomWidth: bw,
                            borderColor: color,
                            borderTopLeftRadius: 3,
                            borderBottomLeftRadius: 3,
                        }}
                    />
                    <View style={{ flexDirection: 'row', alignItems: 'center', marginLeft: -size * 0.15 }}>
                        <View style={{ width: size * 0.38, height: bw, backgroundColor: color }} />
                        <View
                            style={{
                                width: size * 0.24,
                                height: size * 0.24,
                                borderTopWidth: bw,
                                borderRightWidth: bw,
                                borderColor: color,
                                transform: [{ rotate: '45deg' }],
                                marginLeft: -size * 0.18,
                            }}
                        />
                    </View>
                </View>
            );

        case 'home':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.52,
                            height: size * 0.52,
                            borderTopWidth: bw,
                            borderLeftWidth: bw,
                            borderColor: color,
                            transform: [{ rotate: '45deg' }],
                            marginBottom: -size * 0.24,
                        }}
                    />
                    <View
                        style={{
                            width: size * 0.62,
                            height: size * 0.46,
                            borderLeftWidth: bw,
                            borderRightWidth: bw,
                            borderBottomWidth: bw,
                            borderColor: color,
                            borderBottomLeftRadius: 2,
                            borderBottomRightRadius: 2,
                        }}
                    />
                </View>
            );

        case 'user':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.36,
                            height: size * 0.36,
                            borderRadius: size * 0.18,
                            borderWidth: bw,
                            borderColor: color,
                            marginBottom: 2,
                        }}
                    />
                    <View
                        style={{
                            width: size * 0.68,
                            height: size * 0.32,
                            borderTopLeftRadius: size * 0.34,
                            borderTopRightRadius: size * 0.34,
                            borderWidth: bw,
                            borderBottomWidth: 0,
                            borderColor: color,
                        }}
                    />
                </View>
            );

        case 'users':
            return (
                <View style={[box, { flexDirection: 'row' }]}>
                    <View style={{ alignItems: 'center' }}>
                        <View style={{ width: size * 0.32, height: size * 0.32, borderRadius: size * 0.16, borderWidth: bw, borderColor: color, marginBottom: 1.5 }} />
                        <View style={{ width: size * 0.54, height: size * 0.28, borderTopLeftRadius: size * 0.27, borderTopRightRadius: size * 0.27, borderWidth: bw, borderBottomWidth: 0, borderColor: color }} />
                    </View>
                    <View style={{ alignItems: 'center', marginLeft: -size * 0.12 }}>
                        <View style={{ width: size * 0.26, height: size * 0.26, borderRadius: size * 0.13, borderWidth: bw, borderColor: color, marginBottom: 1.5 }} />
                        <View style={{ width: size * 0.42, height: size * 0.24, borderTopLeftRadius: size * 0.21, borderTopRightRadius: size * 0.21, borderWidth: bw, borderBottomWidth: 0, borderColor: color }} />
                    </View>
                </View>
            );

        case 'shield':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.64,
                            height: size * 0.74,
                            borderWidth: bw,
                            borderColor: color,
                            borderTopLeftRadius: 3,
                            borderTopRightRadius: 3,
                            borderBottomLeftRadius: size * 0.34,
                            borderBottomRightRadius: size * 0.34,
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View style={{ width: bw, height: size * 0.3, backgroundColor: color, borderRadius: 1 }} />
                    </View>
                </View>
            );

        case 'headset':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.72,
                            height: size * 0.62,
                            borderTopLeftRadius: size * 0.36,
                            borderTopRightRadius: size * 0.36,
                            borderTopWidth: bw,
                            borderLeftWidth: bw,
                            borderRightWidth: bw,
                            borderColor: color,
                            flexDirection: 'row',
                            justifyContent: 'space-between',
                            alignItems: 'flex-end',
                        }}
                    >
                        <View style={{ width: size * 0.18, height: size * 0.28, backgroundColor: color, borderRadius: 2 }} />
                        <View style={{ width: size * 0.18, height: size * 0.28, backgroundColor: color, borderRadius: 2 }} />
                    </View>
                </View>
            );

        case 'wrench':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.38,
                            height: size * 0.38,
                            borderRadius: size * 0.19,
                            borderWidth: bw,
                            borderColor: color,
                            marginBottom: -2,
                        }}
                    />
                    <View
                        style={{
                            width: bw * 1.4,
                            height: size * 0.42,
                            backgroundColor: color,
                            borderRadius: 1,
                            transform: [{ rotate: '30deg' }],
                        }}
                    />
                </View>
            );

        case 'laptop':
            return (
                <View style={box}>
                    <View style={{ width: size * 0.76, height: size * 0.52, borderRadius: 2.5, borderWidth: bw, borderColor: color }} />
                    <View style={{ width: bw, height: size * 0.12, backgroundColor: color }} />
                    <View style={{ width: size * 0.44, height: bw, backgroundColor: color, borderRadius: 1 }} />
                </View>
            );

        case 'gear':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.72,
                            height: size * 0.72,
                            borderRadius: size * 0.36,
                            borderWidth: bw * 1.3,
                            borderStyle: 'dashed',
                            borderColor: color,
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View style={{ width: size * 0.28, height: size * 0.28, borderRadius: size * 0.14, borderWidth: bw, borderColor: color }} />
                    </View>
                </View>
            );

        case 'globe':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.76,
                            height: size * 0.76,
                            borderRadius: size * 0.38,
                            borderWidth: bw,
                            borderColor: color,
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View style={{ position: 'absolute', width: size * 0.7, height: bw * 0.85, backgroundColor: color }} />
                        <View style={{ width: size * 0.36, height: size * 0.72, borderRadius: size * 0.18, borderWidth: bw * 0.85, borderColor: color }} />
                    </View>
                </View>
            );

        case 'pin':
        case 'location':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.58,
                            height: size * 0.58,
                            borderTopLeftRadius: size * 0.29,
                            borderTopRightRadius: size * 0.29,
                            borderBottomLeftRadius: size * 0.29,
                            borderBottomRightRadius: 2,
                            borderWidth: bw,
                            borderColor: color,
                            transform: [{ rotate: '45deg' }],
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View style={{ width: size * 0.18, height: size * 0.18, borderRadius: size * 0.09, backgroundColor: color }} />
                    </View>
                </View>
            );

        case 'calendar':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.74,
                            height: size * 0.68,
                            borderRadius: 3,
                            borderWidth: bw,
                            borderColor: color,
                            overflow: 'hidden',
                        }}
                    >
                        <View style={{ width: '100%', height: size * 0.2, backgroundColor: color }} />
                    </View>
                </View>
            );

        case 'clock':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.76,
                            height: size * 0.76,
                            borderRadius: size * 0.38,
                            borderWidth: bw,
                            borderColor: color,
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View
                            style={{
                                width: size * 0.22,
                                height: size * 0.24,
                                borderLeftWidth: bw,
                                borderBottomWidth: bw,
                                borderColor: color,
                                marginTop: -size * 0.08,
                                marginRight: -size * 0.08,
                            }}
                        />
                    </View>
                </View>
            );

        case 'play':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: 0,
                            height: 0,
                            borderTopWidth: size * 0.3,
                            borderBottomWidth: size * 0.3,
                            borderLeftWidth: size * 0.48,
                            borderTopColor: 'transparent',
                            borderBottomColor: 'transparent',
                            borderLeftColor: color,
                            marginLeft: 2,
                        }}
                    />
                </View>
            );

        case 'check':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.56,
                            height: size * 0.3,
                            borderLeftWidth: bw * 1.2,
                            borderBottomWidth: bw * 1.2,
                            borderColor: color,
                            transform: [{ rotate: '-45deg' }],
                            marginTop: -2,
                        }}
                    />
                </View>
            );

        case 'refresh':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.68,
                            height: size * 0.68,
                            borderRadius: size * 0.34,
                            borderWidth: bw,
                            borderColor: color,
                            borderRightColor: 'transparent',
                        }}
                    />
                </View>
            );

        case 'alert':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.74,
                            height: size * 0.74,
                            borderRadius: size * 0.37,
                            borderWidth: bw,
                            borderColor: color,
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <Text style={{ color, fontSize: size * 0.54, fontWeight: 'bold', lineHeight: size * 0.6 }}>!</Text>
                    </View>
                </View>
            );

        case 'search':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.54,
                            height: size * 0.54,
                            borderRadius: size * 0.27,
                            borderWidth: bw,
                            borderColor: color,
                            marginTop: -size * 0.12,
                            marginLeft: -size * 0.12,
                        }}
                    />
                    <View
                        style={{
                            position: 'absolute',
                            width: size * 0.28,
                            height: bw,
                            backgroundColor: color,
                            bottom: size * 0.2,
                            right: size * 0.14,
                            transform: [{ rotate: '45deg' }],
                        }}
                    />
                </View>
            );

        case 'plus':
            return (
                <View style={box}>
                    <View style={{ position: 'absolute', width: size * 0.66, height: bw * 1.2, backgroundColor: color, borderRadius: 1 }} />
                    <View style={{ position: 'absolute', width: bw * 1.2, height: size * 0.66, backgroundColor: color, borderRadius: 1 }} />
                </View>
            );

        case 'clipboard':
            return (
                <View style={box}>
                    <View style={{ width: size * 0.34, height: size * 0.14, borderWidth: bw, borderColor: color, borderRadius: 2, marginBottom: -bw, zIndex: 1, backgroundColor: 'transparent' }} />
                    <View style={{ width: size * 0.64, height: size * 0.68, borderWidth: bw, borderColor: color, borderRadius: 3 }} />
                </View>
            );

        case 'folder':
            return (
                <View style={box}>
                    <View style={{ alignSelf: 'flex-start', marginLeft: size * 0.12, width: size * 0.32, height: size * 0.14, borderTopWidth: bw, borderLeftWidth: bw, borderRightWidth: bw, borderColor: color, borderTopLeftRadius: 2, borderTopRightRadius: 2 }} />
                    <View style={{ width: size * 0.76, height: size * 0.52, borderWidth: bw, borderColor: color, borderRadius: 3, marginTop: -1 }} />
                </View>
            );

        case 'document':
        case 'document-text':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.62,
                            height: size * 0.76,
                            borderWidth: bw,
                            borderColor: color,
                            borderRadius: 2.5,
                            justifyContent: 'space-evenly',
                            alignItems: 'center',
                            paddingVertical: 3,
                        }}
                    >
                        <View style={{ width: size * 0.36, height: bw * 0.85, backgroundColor: color }} />
                        <View style={{ width: size * 0.36, height: bw * 0.85, backgroundColor: color }} />
                    </View>
                </View>
            );

        case 'camera':
            return (
                <View style={box}>
                    <View style={{ width: size * 0.32, height: size * 0.12, backgroundColor: color, borderTopLeftRadius: 2, borderTopRightRadius: 2 }} />
                    <View
                        style={{
                            width: size * 0.76,
                            height: size * 0.54,
                            borderWidth: bw,
                            borderColor: color,
                            borderRadius: 3,
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View style={{ width: size * 0.26, height: size * 0.26, borderRadius: size * 0.13, borderWidth: bw, borderColor: color }} />
                    </View>
                </View>
            );

        case 'trash':
            return (
                <View style={box}>
                    <View style={{ width: size * 0.68, height: bw, backgroundColor: color, marginBottom: 1.5 }} />
                    <View style={{ width: size * 0.54, height: size * 0.6, borderWidth: bw, borderTopWidth: 0, borderColor: color, borderBottomLeftRadius: 2.5, borderBottomRightRadius: 2.5 }} />
                </View>
            );

        case 'lock':
            return (
                <View style={box}>
                    <View style={{ width: size * 0.38, height: size * 0.26, borderTopLeftRadius: size * 0.19, borderTopRightRadius: size * 0.19, borderWidth: bw, borderBottomWidth: 0, borderColor: color }} />
                    <View style={{ width: size * 0.62, height: size * 0.46, borderRadius: 2.5, borderWidth: bw, borderColor: color }} />
                </View>
            );

        case 'id-card':
            return (
                <View style={box}>
                    <View style={{ width: size * 0.78, height: size * 0.54, borderRadius: 3, borderWidth: bw, borderColor: color, justifyContent: 'center', paddingHorizontal: 3 }}>
                        <View style={{ width: size * 0.45, height: bw, backgroundColor: color, marginBottom: 2 }} />
                        <View style={{ width: size * 0.3, height: bw * 0.85, backgroundColor: color }} />
                    </View>
                </View>
            );

        case 'close':
            return (
                <View style={box}>
                    <View style={{ position: 'absolute', width: size * 0.68, height: bw * 1.15, backgroundColor: color, transform: [{ rotate: '45deg' }] }} />
                    <View style={{ position: 'absolute', width: size * 0.68, height: bw * 1.15, backgroundColor: color, transform: [{ rotate: '-45deg' }] }} />
                </View>
            );

        case 'arrow-left':
            return (
                <View style={[box, { flexDirection: 'row' }]}>
                    <View
                        style={{
                            width: size * 0.28,
                            height: size * 0.28,
                            borderBottomWidth: bw * 1.1,
                            borderLeftWidth: bw * 1.1,
                            borderColor: color,
                            transform: [{ rotate: '45deg' }],
                            marginRight: -size * 0.22,
                        }}
                    />
                    <View style={{ width: size * 0.55, height: bw * 1.1, backgroundColor: color }} />
                </View>
            );

        case 'face-id': {
            const corner = size * 0.24;
            const r = Math.max(3, size * 0.12);
            return (
                <View style={[box, { position: 'relative' }]}>
                    {/* 4 esquinas del marco Face ID */}
                    <View style={{ position: 'absolute', top: size * 0.1, left: size * 0.1, width: corner, height: corner, borderTopWidth: bw, borderLeftWidth: bw, borderColor: color, borderTopLeftRadius: r }} />
                    <View style={{ position: 'absolute', top: size * 0.1, right: size * 0.1, width: corner, height: corner, borderTopWidth: bw, borderRightWidth: bw, borderColor: color, borderTopRightRadius: r }} />
                    <View style={{ position: 'absolute', bottom: size * 0.1, left: size * 0.1, width: corner, height: corner, borderBottomWidth: bw, borderLeftWidth: bw, borderColor: color, borderBottomLeftRadius: r }} />
                    <View style={{ position: 'absolute', bottom: size * 0.1, right: size * 0.1, width: corner, height: corner, borderBottomWidth: bw, borderRightWidth: bw, borderColor: color, borderBottomRightRadius: r }} />
                    {/* Ojos */}
                    <View style={{ flexDirection: 'row', justifyContent: 'space-between', width: size * 0.34, marginTop: -size * 0.04 }}>
                        <View style={{ width: bw * 1.1, height: size * 0.13, backgroundColor: color, borderRadius: bw }} />
                        <View style={{ width: bw * 1.1, height: size * 0.13, backgroundColor: color, borderRadius: bw }} />
                    </View>
                    {/* Nariz */}
                    <View style={{ width: bw, height: size * 0.13, backgroundColor: color, borderRadius: bw, marginTop: size * 0.02 }} />
                    {/* Sonrisa */}
                    <View style={{ width: size * 0.28, height: size * 0.1, borderBottomWidth: bw, borderLeftWidth: bw * 0.6, borderRightWidth: bw * 0.6, borderColor: color, borderBottomLeftRadius: size * 0.14, borderBottomRightRadius: size * 0.14, marginTop: size * 0.03 }} />
                </View>
            );
        }

        case 'fingerprint':
            return (
                <View style={box}>
                    <View
                        style={{
                            width: size * 0.78,
                            height: size * 0.84,
                            borderRadius: size * 0.39,
                            borderWidth: bw,
                            borderColor: color,
                            borderBottomColor: 'transparent',
                            justifyContent: 'center',
                            alignItems: 'center',
                        }}
                    >
                        <View
                            style={{
                                width: size * 0.52,
                                height: size * 0.58,
                                borderRadius: size * 0.26,
                                borderWidth: bw,
                                borderColor: color,
                                borderBottomColor: 'transparent',
                                justifyContent: 'center',
                                alignItems: 'center',
                            }}
                        >
                            <View
                                style={{
                                    width: size * 0.24,
                                    height: size * 0.3,
                                    borderRadius: size * 0.12,
                                    borderWidth: bw,
                                    borderColor: color,
                                    borderBottomColor: 'transparent',
                                }}
                            />
                        </View>
                    </View>
                </View>
            );

        case 'arrow-right':
        default:
            return (
                <View style={[box, { flexDirection: 'row' }]}>
                    <View style={{ width: size * 0.55, height: bw * 1.1, backgroundColor: color }} />
                    <View
                        style={{
                            width: size * 0.28,
                            height: size * 0.28,
                            borderTopWidth: bw * 1.1,
                            borderRightWidth: bw * 1.1,
                            borderColor: color,
                            transform: [{ rotate: '45deg' }],
                            marginLeft: -size * 0.22,
                        }}
                    />
                </View>
            );
    }
};
