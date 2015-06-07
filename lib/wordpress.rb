class WordPress
  include HTTParty

  def initialize(uri)
    self.class.base_uri uri
  end

  def pages
    @pages ||= get_pages
  end

  protected

    def get_pages
      pages = []
      page  = 1
      limit = 10

      tmp_pages = fetch_pages('page', page, limit)

      while !tmp_pages.empty?
        pages.concat tmp_pages
        page = page + 1
        tmp_pages = fetch_pages('page', page, limit)
      end

      return pages
    end

    def fetch_pages(type, page, limit)
      self.class.get("/pages?type=#{type}&page=#{page}&pages_per_page=#{limit}")
    end
end
