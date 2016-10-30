import re
import os
import nltk
from nltk.corpus import treebank
from nltk.tag import hmm
from nltk.util import unique_list, LazyMap

# Parsing training data into list of lists of word and tag
# Ex [[("I", "B"), ("like", "I"), ("this", "O"), (".", "O")],
#     [("He", "B"), ("needs", "I"), ("coat", "O"), (".", "O")]]
def process_train_data():
  train_data = []
  for filename in os.listdir("train"):
    sentence = []
    if filename != ".DS_Store":
      with open("train/"+filename, 'r+') as f:
        for line in f.read().splitlines():
          lineSplit = re.split('\t+', line)
          if (len(lineSplit) < 3):
            continue
          else:
            sentence.append((lineSplit[0], lineSplit[2]))
    train_data.append(sentence)
  # Dirty fix to get rid of empty list. Fix when done.
  if len(train_data[0]) == 0:
    train_data.pop(0)
  return train_data

# Parsing Test data to feed into HMM
def parse_test_data():
  sent = ""
  for filename in os.listdir("test-private"):
    if filename != ".DS_Store":
      with open("test-private/"+filename, 'r+') as f:
        for line in f.read().splitlines():
          lineSplit = re.split('\t+', line)
          if (len(lineSplit) < 1):
            continue
          else:
            sent += lineSplit[0] + " "
    if len(sent) == 0:
      continue
  return sent
# train_data = LazyMap(train_data)

# FORM 1 only one list FOR KAGGLE 2.1 just print test_tag here (COMMENT OUT FROM
# sent_tag_lst = [] and below)
def train_hmm_word(sent, train_data):
  symbols = unique_list(word for sent in train_data
              for word, tag in sent)

  # List of tags: gives back ["B", "I", "O"]
  tag_set = unique_list(tag for sent in train_data
              for word, tag in sent)

  trainer = hmm.HiddenMarkovModelTrainer(tag_set)
  tagger = trainer.train_supervised(train_data)

  sent_lst = sent.split()
  i = 0
  test_tag = []
  while (i < len(sent_lst)):
    end = min(i+5, len(sent_lst))
    test_tag += tagger.tag(sent_lst[i:end])
    i += 5

  # print(test_tag)
  return test_tag

# This is for KAGGLE 2.2 (it will give back a list of lists)
# Ex. [[('placed', 'O'), ('nearby', 'O'), (';', 'O'), ('these', 'O'),
# ('punishments', 'O'), ('generally', 'O')], [('lasted', 'O'), ('only', 'O')]]
def train_hmm_sent(sent, train_data, test_tag):
  # Converts list to list of lists (list of sentences)
  sent_tag_lst = []
  sent = []
  for tag in test_tag:
    if (tag[0] == '.'):
      sent_tag_lst.append(sent)
      sent = []
    else:
      sent.append(tag)

  # print(sent_tag_lst)
  output = sent_tag_lst
  numlist = []
  sCount= 0
  for sentence in output:
    for wordpair in sentence:
      if wordpair[1]== 'B':
        if sCount not in numlist:
          numlist.append(sCount)
    sCount = sCount + 1

  print(numlist)

def main():
  train_data = process_train_data()
  sent = parse_test_data()
  test_tag = train_hmm_word(sent, train_data)
  train_hmm_sent(sent, train_data, test_tag)

if __name__ == "__main__":
  main()


