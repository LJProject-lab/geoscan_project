# geoscan_project
Geolocator and Fingerprint Scan Project

## Pre-requisites and Requirements for Time In/Time Out System

### 1. Device Requirements:
- **Fingerprint Time In/Out:**
  - The device must have a built-in fingerprint scanner.
  - The device should support browser-based fingerprint authentication.
  - The device must be registered with the user's student ID in the system.

- **4-Pin Time In/Out:**
  - The device must have a functional camera to capture photos/selfies.
  - The device must be connected to the internet to submit the photo/selfie along with the time log.
  - Users must have their 4-digit PIN set up in the system.

### 2. User Account Setup:
- **Fingerprint Time In/Out:**
  - Users must register their device with their student ID.
  - The device registration process includes linking the device's fingerprint authentication with the student ID.
  - Users must successfully authenticate their fingerprint on the registered device for time in/out.

- **4-Pin Time In/Out:**
  - Users must register their 4-digit PIN in the system.
  - Users must be aware of their student ID and PIN to log their time.
  - Users must be ready to capture a selfie or photo each time they log in or out.

### 3. Location Verification (for 4-Pin Method):
- Users must allow the application to access their device's location services.
- The captured selfie/photo will be compared with the location data to ensure the user is at the correct location.

### 4. Internet Connectivity:
- Both methods require an active internet connection to submit the time logs to the system.

## Limitations

### 1. Fingerprint Scanning Limitations:
- **Device Dependency:** Only devices with built-in fingerprint scanners can use the fingerprint method. Users without such devices must use the 4-pin method.
- **No Cross-Device Use:** Once a device is registered with a student ID, time logging can only occur on that device. Other devices cannot be used to log time with that student ID.
- **No Fingerprint Storage:** The system does not store or register users' fingerprint data; it relies solely on the device's native authentication mechanism.

### 2. 4-Pin Time In/Out Limitations:
- **Photo Requirement:** Each 4-pin time log must be accompanied by a photo/selfie for verification purposes. This adds a step to the process and requires users to have a working camera on their device.
- **Manual Input:** Users must manually input their student ID and PIN, which could be less convenient than the fingerprint method.
- **Location Verification:** The accuracy of location data depends on the device's GPS capabilities and the strength of the internet connection.
