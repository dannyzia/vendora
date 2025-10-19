<div>
    <h3>Reviews</h3>

    @foreach ($reviews as $review)
        <div>
            <h4>{{ $review->title }}</h4>
            <p>{{ $review->comment }}</p>
            <div>
                Rating: {{ $review->rating }}
            </div>
        </div>
    @endforeach

    <form action="{{ route('reviews.store', $product) }}" method="POST">
        @csrf
        <h4>Leave a review</h4>
        <div>
            <label for="rating">Rating</label>
            <select name="rating" id="rating">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
        </div>
        <div>
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" cols="30" rows="5"></textarea>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
