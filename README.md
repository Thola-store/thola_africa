# Thola Africa Shopping Site

## Getting started

### Step 1: Install MAMP on Your Computer

```
 - Install MAMP server on https://www.mamp.info/en/
```

### Step 2: Launching MAMP From Your Applications Folder

```
 - Go into your Applications folder (where MAMP should have been saved in the previous step) and click the MAMP folder.
 - Click on the elephant MAMP icon
```

### Step 3: Setting the Ports

```
 - Click Preferences, and make sure the Ports tab is selected.
 - Set on the default: 8888 for Apache and leave everything as default.
```

### Step 4: Configure the Web Server, Document Root

```
 - 4.1 Now click on the Web Server tab. Make sure the web server selected  is Apache.
 - 4.2 Set the path: Users > 'yourname' > Sites or create your own folder where your site will leave in like folder Site.
```

### Step 5: Download GIT for windows and clone the project

```
 - 5.1 Navigate to the site https://desktop.github.com/ and download
 - 5.2 Open the downloaded git after setup, look for 'CLONE' click and change root folder to 4.2 and click 'URL' enter 'https://github.com/Thola-store/thola_africa.git'
```


### Step 6: Create DB and Run the Site

```
 - 6.1 Open MAMP step 2, click on power like button, it will open the browser and like the myPHPAdmin
 - 6.2 Create new DB name it "new_Thola" and after click "Import" and navigate to the downloaded sql script and run it
 - 6.3 Go to 'SQL' tab and run this scripts 
 		- UPDATE wp_options SET option_value=replace(option_value, 'https://tholaafrica.com','http://localhost:8888/thola_africa') WHERE option_name='home' OR option_name='siteurl';
		- UPDATE wp_posts SET post_content=replace(post_content,'https://tholaafrica.com','http://localhost:8888/thola_africa')
```


## Usage

```
 - Browse to http://localhost:8888/thola_africa/wp-admin/
```


For more information please see the [Woo Commerce ](https://woocommerce.com/) documentation.
