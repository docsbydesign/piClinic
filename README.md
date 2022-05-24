# piClinic

This repo contains the system software for the piClinic information system.
For more info, see the [project website](https://piclinic.org). For information
on how to contribute to the piClinic, see the
[Contributing](https://github.com/docsbydesign/piClinic/blob/main/CONTRIBUTING.md) guidelines.

## Details about this repo

This section describes the organization of this repo.

### Folders

This section describes the general purpose of each of the folders in this repo.

#### docs

The project website hosted by GitHub Pages at [https://piclinic.org](https://piclinic.org).

#### notes

Design notes and thoughts that do not belong in the published documentation, but should be kept with the project files.

#### postman

Regression tests for the Postman tool

#### sql

Database definitions and initialization scripts

#### tools

Misc. tools to help build/update the software and development notes.

#### uitext

Source files for localized strings.

#### www

The _**piClinic** Console_ software. This should map directly to the /var/www folder in the target system.
The files in the `pass` directory must be updated for each specific installation.
See more detailed installation info in [tools/piClinicSystemSetup.sh](https://github.com/docsbydesign/piClinic/blob/main/tools/piClinicSystemSetup.sh)
or [main/tools/piClinicVMSetup.sh](https://github.com/docsbydesign/piClinic/blob/main/tools/piClinicVMSetup.sh).