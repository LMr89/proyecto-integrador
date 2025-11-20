# 📚 Seven Service - Exportador de Documentación

Este script genera una versión PDF de toda la documentación del proyecto.

## Archivos de Documentación

1. **README.md** - Visión general del proyecto
2. **docs/README_DOCS.md** - Índice general de documentación
3. **docs/FRONTEND_README.md** - Guía para desarrolladores frontend (★ PRINCIPAL)
4. **docs/ARQUITECTURA.md** - Diseño técnico del sistema
5. **docs/INICIO_RAPIDO.md** - Quick start guide
6. **docs/DIAGRAMAS.md** - Diagramas visuales
7. **docs/PRUEBAS.md** - Checklist de testing

## Cómo usar

### Opción 1: Pandoc (Recomendado)

```bash
# Instalar Pandoc: https://pandoc.org/installing.html

# Generar PDF de toda la documentación
pandoc README.md \
       docs/README_DOCS.md \
       docs/FRONTEND_README.md \
       docs/ARQUITECTURA.md \
       docs/INICIO_RAPIDO.md \
       docs/DIAGRAMAS.md \
       docs/PRUEBAS.md \
       -o SevenService_Documentacion_Completa.pdf \
       --toc \
       --toc-depth=3 \
       --number-sections \
       -V geometry:margin=1in

# Generar solo la guía de frontend
pandoc docs/FRONTEND_README.md \
       -o SevenService_API_Frontend_Guide.pdf \
       --toc \
       --number-sections \
       -V geometry:margin=1in
```

### Opción 2: Markdown to PDF (VS Code)

1. Instalar extensión "Markdown PDF" en VS Code
2. Abrir cualquier archivo .md
3. `Ctrl+Shift+P` → "Markdown PDF: Export (pdf)"

### Opción 3: GitHub/GitLab

Subir a GitHub y usar GitHub Pages para generar sitio web de documentación.

## Documentación Web

También puedes acceder a la documentación interactiva en:

```
http://localhost/UNIVERSIDAD/Integrador/7service/public/api-documentation.html
```

## Estructura de Carpeta `docs/`

```
docs/
├── README_DOCS.md           # Índice general ← EMPIEZA AQUÍ
├── FRONTEND_README.md       # ★ Guía frontend (60+ páginas)
├── ARQUITECTURA.md          # Diseño técnico
├── INICIO_RAPIDO.md         # Setup rápido
├── DIAGRAMAS.md            # Diagramas ASCII
├── PRUEBAS.md              # Testing checklist
├── EXPORT_DOCS.md          # Este archivo
└── swagger/
    └── swagger.yaml         # OpenAPI 3.0 spec
```

## Compartir con el Equipo

### Para Frontend Developers:
**Archivo principal:** `docs/FRONTEND_README.md`

Incluye:
- Quick start
- Todos los endpoints
- Ejemplos con React, Vue, JavaScript vanilla
- Manejo de errores
- TypeScript types

### Para Backend Developers:
**Archivos principales:**
- `docs/ARQUITECTURA.md`
- `docs/DIAGRAMAS.md`
- Código fuente en `/app/`

### Para QA/Testers:
**Archivo principal:** `docs/PRUEBAS.md`

### Para Nuevos Integrantes:
**Secuencia recomendada:**
1. `README.md`
2. `docs/INICIO_RAPIDO.md`
3. `docs/README_DOCS.md` (índice)
4. Archivo específico según rol

## Exportar para Presentación

```bash
# Generar presentación Powerpoint
pandoc docs/FRONTEND_README.md -o Frontend_API_Guide.pptx

# Generar HTML standalone
pandoc docs/FRONTEND_README.md -s -o api-guide.html --toc
```

## Changelog

- **v1.0.0** (Oct 2025): Documentación inicial completa
  - README general
  - Guía frontend completa
  - Arquitectura documentada
  - Diagramas de flujo
  - Checklist de pruebas
  - Documentación web interactiva
