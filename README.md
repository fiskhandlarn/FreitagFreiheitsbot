# FreitagFreiheitsbot
Ist heute Freitag?

## Install

```bash
make install
```

Edit the newly created `.env` file with the database credentials.

## Docker

Start Docker:

```bash
make up
```

Access the site via https://localhost/.

## Quality assurance

### Code syntax

```bash
composer run lint
```

## Deploy

[/.github/workflows/cicd.prod.yml](./.github/workflows/cicd.prod.yml)

