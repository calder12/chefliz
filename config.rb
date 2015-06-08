# Require custom libraries
require 'lib/wordpress'

# Do not compile template files used when dynamically generating pages
ignore 'templates/*'

# Create a new instance of WordPress. Replace the URL with the URL of your
# rest server endpoint.
wordpress = WordPress.new('http://calderonline.com/liz-admin/wp-json')

# Dynamically generate pages using wordpress data
wordpress.posts.each do |post|
  post['terms']['category'].each do |cat|
    if cat['slug'].include?('front-page')
      proxy "/index.html", "templates/post.html", locals: { post: post }
    else
      proxy "/#{post['slug']}/index.html", "templates/post.html", locals: { post: post }
    end
  end
end


# Make the WordPress instance available to templates via a helper
helpers do
  def wp
    wordpress
  end
end

# Create a pageable set of posts
activate :pagination do
  pageable_set :posts do
    wordpress.posts
  end
end

set :css_dir, 'assets/css'
set :js_dir, 'assets/js'
set :images_dir, 'assets/img'

# Build-specific configuration
configure :build do
  # Minify on build
  activate :minify_css
  activate :minify_javascript
  activate :minify_html

  # Enable cache buster
  activate :asset_hash

  # Use relative URLs
  # activate :relative_assets

  # Or use a different image path
  # set :http_prefix, "/Content/images/"
end

activate :deploy do |deploy|
  deploy.build_before = true # default: false
  deploy.method = :rsync
  deploy.host   = 'calderonline.com'
  deploy.path   = 'public_html/liz/'
  # Optional Settings
   deploy.user  = 'akramonl' # no default
  # deploy.port  = 5309 # ssh port, default: 22
  # deploy.clean = true # remove orphaned files on remote host, default: false
  # deploy.flags = '-rltgoDvzO --no-p --del' # add custom flags, default: -avz
end

activate :autoprefixer
activate :livereload