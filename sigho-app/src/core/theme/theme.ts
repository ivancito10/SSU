export const theme = {
    colors: {
        // Colores extraídos de la app institucional SigHov2
        primary: '#2B548F', // Azul institucional (Cabeceras, botones principales, barra inferior)
        secondary: '#189C63', // Verde (Usado en botones como "Nueva Cita" o "Disponibles")
        background: '#F5F7FA', // Gris muy claro para el fondo de la aplicación
        surface: '#FFFFFF', // Blanco puro para las tarjetas (Cards)

        // Textos
        textPrimary: '#333333', // Gris oscuro para lectura
        textSecondary: '#666666', // Gris medio para subtítulos o fechas
        textLight: '#FFFFFF', // Texto blanco sobre botones azules/verdes

        // Estados (Para las etiquetas de los tickets de soporte)
        danger: '#E74C3C', // Rojo para "Suspendido" o Alta Prioridad
        warning: '#F39C12', // Naranja para En Proceso
        info: '#3498DB', // Azul claro para Total Fichas / Asignado

        // Bordes
        border: '#E0E0E0',
    },
    spacing: {
        xs: 4,
        sm: 8,
        md: 16,
        lg: 24,
        xl: 32,
    },
    borderRadius: {
        sm: 4,
        md: 8, // Bordes redondeados estándar de las tarjetas en la imagen
        lg: 16,
        pill: 9999, // Para botones completamente redondeados como el de "Nueva Cita"
    },
    typography: {
        sizes: {
            sm: 12,
            md: 14,
            lg: 16,
            xl: 20,
            title: 24,
        },
        weights: {
            regular: '400' as const,
            medium: '500' as const,
            bold: '700' as const,
        }
    },
    shadows: {
        // Sombra suave para que las tarjetas blancas resalten sobre el fondo gris
        card: {
            shadowColor: '#000',
            shadowOffset: { width: 0, height: 2 },
            shadowOpacity: 0.1,
            shadowRadius: 4,
            elevation: 3, // Para Android
        }
    }
};