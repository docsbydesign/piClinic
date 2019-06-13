---
title: "piClinic Console"
permalink: /cts/

---

# _piClinic Console_

[![piClinic Console System - Front]({{site.baseurl}}/images/TermFront-piclinic_sm.jpg)]({{site.baseurl}}/images/TermFront-piclinic_lg.jpg)

The _**piClinic** Console_ is a low-cost, computer solution that automates many of the most time-consuming administrative tasks in a small clinic to improve patient management and clinic reporting. Adopting the _**piClinic** Console_ helps a limited-resource clinic move from a paper-based patient record system to automated systems while providing immediate benefits to the clinic staff and patients.

[![piClinic Console System - Rear]({{site.baseurl}}/images/TermRear_sm.jpg)]({{site.baseurl}}/images/TermRear_lg.jpg)

The _**piClinic** Console_ is built on open-source software, low-cost hardware, and is designed for the clinic environment to offer an affordable and sustainable patient-information solution for clinics with limited resources. The basic _**piClinic** Console_ configuration consists of a single monitor with the processor module mounted on the back. The standard USB mouse and keyboard connect to the processor module.

The _**piClinic** Console_ can operate effectively in a clinic without requiring any additional resources such as printer supplies or other consumable products. At the same time, the _**piClinic** Console_ supports such devices as bar-code scanners, form and report printers, electronic data transfer using USB memories, and email of reports using cell phones. 

<a id="status" />
## Current Project Status

[Project milestones](#milestones)

| -------- | -------- |
| Prototype | In [Field testing](#summer2019) |
| Software demo | Available online  |

Contact [Robert Watson](mailto://watson_rb@mercer.edu) for more info or to access the online demo.

## Technical details

The _**piClinic** Console_ consists of the hardware and software described in the following tables. The hardware to build a single _**piClinic** Console_  sells for about $240 through online stores such as Amazon. A battery-backed UPS to support power interuptions or poor ambient power quality and run the system for about 60 minutes costs about $70.

### System hardware

| -------- | -------- |
| Processor | Raspberry Pi 3 model B|
| System storage | 32 GB micro-SDHC (the faster, the better) |
| Backup storage | 32 GB USB |
| Monitor | 19-22" LCD (HDMI)|
| Power | 110 VAC, 60 Hz, 25 watts nominal power consumption<br>(3 watts in power-save mode) |

[piClinic Console detailed parts list]({{site.baseurl}}/piclinic-console-parts/)

### System software

| -------- | -------- |
| Operating System | Linux Raspbian Stretch |
| Web Server | Apache 2.x|
| Application Software | PHP 7.0 |
| Database Software | MySQL 5.7 |

<a id="milestones" />
## Project Milestones

Major project accomplishments

<a id="phase1a"></a>
### Phase 1a (Fall 2017 semester)

Goal: test software configuration and confirm data requirements

1. Hardware selected and configured 
1. Patient Master Index functionally complete
1. Patient ID images functionally complete
1. Visit tracking functionally complete
1. Patient visit images functionally complete
1. Initial report set working
	* Clinic daily log
	* Clinic monthly statistics

* No JavaScript in pages, HTML-only UI
* No UI styling
* No system management functionality

<a id="phase1b"></a>
### Phase 1b (Spring 2018 semester)

Goal: prepare for field test<br>
Accomplished: Presented demo to stakeholders and identified additional features.

1. (&#10003;) Hardware tested and verified ready for field test
1. (&#10003;) Visit tracking functionally complete
1. (_) Sytem management functions complete
	* (&#10003;) User account configuration
	* (_) Clinic configuration
	* (_) Backup/restore
	* (_) System upgrade
	* (_) Data-security features implemented
1. (_) Onboard training complete
1. (_) Onboard help complete
1. (&#10003;) Field-test UI complete

<a id="summer2018"></a>
### Customer review and Beta-test site evaluations (Summer 2018)

* Identify five clinics willing to field-test the system
* Review system features and requirements with Honduran stakeholders
* Identify logistical assets for summer 2019
* Conduct usability tests

### Field-test preparation (Fall 2018, Spring 2019)

* Refactor system to be customer-ready by Summer 2019
* Revise system, training, and documentation for production
* Investigate multi-user clinic system and multi-user portable system

<a id="summer2019"></a>
### Field-test deployment (Summer 2019)

* Deploy systems into field-test clinics
* _Currently in progress_