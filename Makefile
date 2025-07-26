build-page:
	$(MAKE) clean
	$(MAKE) download-bgg-data
	$(MAKE) generate-html

download-bgg-data:
	php download-bgg-data.php
generate-html:
	$(MAKE) clean
	mkdir build
	php generate-html.php
	cp -r assets/* build/
clean:
	rm -rf build