# iFUSE: Fusion Gene Explorer

# Running iFUSE

First, get the Docker image from Quay.io:

```bash
$ docker pull quay.io/erasmusmc_bioinformatics/ifuse
```

Next, run the Docker container

```bash
$ docker run -p 8080:80 erasmusmc_bioinformatics/ifuse
```

Next, open your browser, and navigate to `localhost:8080`

You should now see the iFUSE home page.

# How to use iFUSE

1. Create an account by clicking on "Register"
2. Upload a file (example file in this repo in `test-data` folder)
3. Provide metadata
   - file type (`Complete Genomics` for our example dataset)
   - reference genome (leave to `hg18` for example dataset)
4. Upload
5. Wait
   - iFUSE will now analyse your dataset, this may take a few minutes if your data is large
6. Explore fusion genes
   - You will be brought to the results page with visualisations of all candidate fusion events
   - Right-click an image to get more information (e.g. details view)




