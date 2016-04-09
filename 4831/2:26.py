forward = "This book should be read almost as though it were science fiction. It is designed to appeal to the imagination. But it is not science fiction: it is science. Cliche or not, 'stranger than fiction' expresses exactly how I feel about the truth. We are survival machines-robot vehicles blindly programmed to preserve the selfish molecules known as genes. This is a truth which still fills me with astonishment. Though I have known it for years, I never seem to get fully used to it. One of my hopes is that I may have some success in astonishing others."

words = forward.split()
word_count = {}

for w in words:
	if w not in word_count:
		word_count[w] = 0
	else word_count[w] += 1
print sorted(word_count.items(), key=lambda t: t[::-1], reverse=True)