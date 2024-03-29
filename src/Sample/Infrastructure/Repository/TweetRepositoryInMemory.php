<?php

namespace App\Sample\Infrastructure\Repository;

use App\Sample\Domain\Model\Tweet;
use App\Sample\Domain\Types\TweetLimit;
use App\Sample\Domain\Types\TwitterName;
use Symfony\Component\HttpFoundation\Request;
use App\Sample\Domain\Interfaces\Repository\TweetRepositoryInMemoryInterface;

/**
 * Class TweetRepositoryInMemory
 *
 * @package App\Sample\Infrastructure\Repository
 */
final class TweetRepositoryInMemory implements TweetRepositoryInMemoryInterface
{
    /**
     * A temporary array for tests cases'
     *
     * @var array
     */
    private $data = ['/asd/qwe?zxc=1' => '3M9V2MNV2XC23HLN4K67N56M0998'];

    /**
     * Obviously tweets would be comming from a DB. 
     * Here it's just for the simplicity purposes, which is required by the test.
     *
     * An array of tweets'
     */
    private const TWEETS = [
        'User1' => [
            "I am not very skeptical… a good deal of skepticism in a scientific man is advisable to avoid much loss of time, but I have met not a few men, who… have often thus been deterred from experiments or observations which would have proven servicable.",
            "I know that most men, including those at ease with problems of the greatest complexity, can seldom accept even the simplest and most obvious truth if it be such as would oblige them to admit the falsity of conclusions which they have delighted in explaining to colleagues, which they have proudly taught to others, and which they have woven, thread by thread, into the fabric of their lives.",
            "When even the brightest mind in our world has been trained up from childhood in a superstition of any kind, it will never be possible for that mind, in its maturity, to examine sincerely, dispassionately, and conscientiously any evidence or any circumstance which shall seem to cast a doubt upon the validity of that superstition. I doubt if I could do it myself.",
            "Doubt everything or believe everything: these are two equally convenient strategies. With either we dispense with the need for reflection.",
            "If a man is in too big a hurry to give up an error he is liable to give up some truth with it.",
            "It’s like religion. Heresy [in science] is thought of as a bad thing, whereas it should be just the opposite.",
            "You can get into a habit of thought in which you enjoy making fun of all those other people who don’t see things as clearly as you do. We have to guard carefully against it.",
            "New and stirring things are belittled because if they are not belittled, the humiliating question arises, ‘Why then are you not taking part in them?’ ",
            "The easy confidence with which I know another man’s religion is folly teaches me to suspect that my own is also.",
            "I believe there is no source of deception in the investigation of nature which can compare with a fixed belief that certain kinds of phenomena are IMPOSSIBLE.",
            "A danger sign of the lapse from true skepticism in to dogmatism is an inability to respect those who disagree",
            "We should be eternally vigilant against attempts to check the expression of opinions that we loathe.",
            "If you are only skeptical, then no new ideas make it through to you. You become a crotchety old person convinced that nonsense is ruling the world. (There is, of course, much data to support you.) But every now and then, a new idea turns out to be on the mark, valid and wonderful. If you are too much in the habit of being skeptical about everything, you are going to miss or resent it, and either way you will be standing in the way of understanding and progress. ",
            "In philosophical discussion, the merest hint of dogmatic certainty as to finality of statement is an exhibition of folly.",
            "There is a principle which is a bar against all information, which cannot fail to keep a man in everlasting ignorance—that principle is contempt prior to investigation.",
            "It is a capital mistake to theorize before one has data. Insensibly one begins to twist facts to suit theories, instead of theories to suit facts.",
            "Now, my suspicion is that the universe is not only queerer than we suppose, but queerer than we can suppose… I suspect that there are more things in heaven and earth than are dreamed of, in any philosophy",
            "The farther the experiment is from theory, the closer it is to the Nobel Prize.",
            "There are two possible outcomes: If the result confirms the hypothesis, then you’ve made a measurement. If the result is contrary to the hypothesis, then you’ve made a discovery.",
            "Daring ideas are like chessmen moved forward; they may be defeated, but they start a winning game.",
            "If we will only allow that, as we progress, we remain unsure, we will leave opportunities for alternatives. We will not become enthusiastic for the fact, the knowledge, the absolute truth of the day, but remain always uncertain… In order to make progress, one must leave the door to the unknown ajar.",
        ],
        'User2' => [
            "Modern science should indeed arouse in all of us a humility before the immensity of the unexplored and a tolerance for crazy hypotheses.",
            "Almost all really new ideas have a certain aspect of foolishness when they are first produced.",
            "The mind likes a strange idea as little as the body likes a strange protein and resists it with similar energy. It would not perhaps be too fanciful to say that a new idea is the most quickly acting antigen known to science.",
            "When a distinguished but elderly scientist states that something is possible, he is almost certainly right. When he states that something is impossible, he is very probably wrong.",
            "There are some people that if they don’t know, you can’t tell ‘em.",
            "The security provided by a long-held belief system, even when poorly founded, is a strong impediment to progress. General acceptance of a practice becomes the proof of its validity, though it lacks all other merit.",
            "The fact that an opinion has been widely held is no evidence whatever that it is not utterly absurd; indeed in view of the silliness of the majority of mankind, a widespread belief is more likely to be foolish than sensible.",
            "New opinions are always suspected, and usually opposed, without any other reason but because they are not already common.",
            "All great truths begin as blasphemies.",
            "Be not astonished at new ideas; for it is well known to you that a thing does not therefore cease to be true because it is not accepted by many.",
            "As long as we do science, some things will always remain unexplained.",
            "The philosophies of one age have become the absurdities of the next, and the foolishness of yesterday has become the wisdom of tomorrow.",
            "The altar cloth of one aeon is the doormat of the next.” -Mark Twai",
            "We do not understand much of anything, from… the big bang all the way down to the particles in the atoms of a bacterial cell. We have a wilderness of mystery to make our way through in the centuries ahead.",
            "Beware when the great God lets loose a thinker on this planet. Then all things are at risk. It is as when a conflagration has broken out in a great city, and no man knows what is safe, or where it will end.",
            "Nothing is too wonderful to be true if it be consistent with the laws of nature.",
            "It is a fool’s prerogative to utter truths that no one else will speak.",
            "The beginning of knowledge is the discovery of something we do not understand.",
            "The most exciting phrase to hear in science, the one that heralds new discoveries, is not ‘Eureka!’ (I found it!) but ‘That’s funny…’ ",
            "The only solid piece of scientific truth about which I feel totally confident is that we are profoundly ignorant about nature… It is this sudden confrontation with the depth and scope of ignorance that represents the most significant contribution of twentieth-century science to the human intellect.",
            "Sit down before facts like a child, and be prepared to give up every preconceived notion, follow humbly wherever and to whatever abysses Nature leads, or you shall learn nothing.",
        ],
        'User3' => [
            "If we watch ourselves honestly we shall often find that we have begun to argue against a new idea even before it has been completely stated.",
            "When a man finds a conclusion agreeable, he accepts it without argument, but when he finds it disagreeable, he will bring against it all the forces of logic and reason.",
            "Science might be better served when some scientists generate novel ideas while others carp at everything new, than if all scientists could somehow become disinterestedly skeptical.",
            "‘Type one’ error is thinking that something special is happening when nothing special really is happening. ‘Type two’ error is thinking that nothing special is happening, when in fact something rare or infrequent is happening.",
            "William James used to preach the 'will to believe'. For my part, I should wish to preach the 'will to doubt'.... What is wanted is not the will to believe, but the will to find out, which is the exact opposite",
            "I cannot give any scientist of any age better advice than this: the intensity of a conviction that a hypothesis is true has no bearing on whether it is true. The importance of the strength of our conviction is only to provide a proportionately strong incentive to find out if the hypothesis will stand up to critical examination.",
            "It would seem to me… an offense against nature, for us to come on the same scene endowed as we are with the curiosity, filled to overbrimming as we are with questions, and naturally talented as we are for the asking of clear questions, and then for us to do nothing about, or worse, to try to suppress the questions…",
            "Many discoveries must have been stillborn or smothered at birth. We know only those which survived.",
            "The creative person pays close attention to what appears discordant and contradictory… and is challenged by such irregularities.",
            "Genius in truth means little more than the faculty of perceiving in an unhabitual way",
            "Truth is stranger than fiction, but it is because Fiction is obliged to stick to possibilities; Truth isn’t.",
            "Let the mind be enlarged… to the grandeur of the mysteries, and not the mysteries contracted to the narrowness of the mind",
            "Man’s mind stretched to a new idea never goes back to its original dimension.",
            "The test of a first-rate intelligence is the ability to hold two opposed ideas in mind at the same time and still retain the ability to function.",
            "It is the mark of an educated mind to be able to entertain a thought without accepting it.",
            "I can live with doubt and uncertainty and not knowing. I think it is much more interesting to live not knowing than to have answers that might be wrong.",
            "You cannot teach a man anything, you can only help him find it within himself.",
            "The high-minded man must care more for the truth than for what people think.",
            "There are many hypotheses in science which are wrong. That’s perfectly all right; they’re the aperture to finding out what’s right",
            "I personally feel it is presumptuous to believe that man can determine the whole temporal structure of the universe, its evolution, development and ultimate fate from the first nanosecond of creation to the last 10^10 years, on the basis of three or four facts which are not very accurately known and are disputed among the experts.",
        ],
        'User4' => [
            "Man’s greatest asset is the unsettled mind.",
            "The voyage of discovery lies not in seeking new horizons, but in seeing with new eyes.",
            "Research is to see what everybody else has seen, and to think what nobody else has thought.",
            "A man receives only what he is ready to receive… The phenomenon or fact that cannot in any wise be linked with the rest of what he has observed, he does not observe",
            "You cannot depend on your eyes when your imagination is out of focus.",
            "The man who cannot occasionally imagine events and conditions of existence that are contrary to the causal principle as he knows it will never enrich his science by the addition of a new idea.",
            "If we knew what it was we were doing, it would not be called research, would it?",
            "If what we regard as real depends on our theory, how can we make reality the basis of our philosophy? ...But we cannot distinguish what is real about the universe without a theory…it makes no sense to ask if it corresponds to reality, because we do not know what reality is independent of a theory.",
            "Reality is that which, when you stop believing in it, doesn’t go away.",
            "There are children playing in the street who could solve some of my top problems in physics, because they have modes of sensory perception that I lost long ago.",
            "Science for me is very close to art. Scientific discovery is an irrational act. It’s an intuition which turns out to be reality at the end of it—and I see no difference between a scientist developing a marvellous discovery and an artist making a painting.",
            "It is through science that we prove, but through intuition that we discover.",
            "Science… is part and parcel of our knowledge and obscures our insight only when it holds that the understanding given by it is the only kind there is.",
            "The person who thinks there can be any real conflict between science and religion must be either very young in science or very ignorant of religion.",
            "Science is not only compatible with spirituality; it is a profound source of spirituality.",
            "Science without religion is lame. Religion without science is blind.",
            "The intuitive mind is a sacred gift and the rational mind is a faithful servant. We have created a society that honors the servant and has forgotten the gift.",
            "If you restrict the journal to publishing only what pleases the referees, you end up publishing what is popular, and while it does make everyone feel more comfortable, you are guaranteed to miss the occasional breakthrough.",
            "Biologists can be just as sensitive to heresy as theologians.",
            "A new scientific truth does not triumph by convincing its opponents and making them see the light, but rather because its opponents eventually die and a new generation grows up that is familiar with it.",
            "Science advances funeral by funeral.",
        ],
        'User5' => [
            "The discovery of truth is prevented more effectively not by the false appearance of things present and which mislead into error, not directly by weakness of the reasoning powers, but by preconceived opinion, by prejudice.",
            "It is a puzzling thing. The truth knocks on the door and you say, ‘Go away, I’m looking for the truth.’ and so it goes away. Puzzling.",
            "They are ill discoverers that think there is no land when they see nothing but sea.",
            "The universe is wider than our views of it.",
            "Everyone takes the limits of his own vision for the limits of the world.",
            "Who never walks save where he sees men’s tracks makes no discoveries.",
            "In questions of science, the authority of a thousand is not worth the humble reasoning of a single individual.",
            "It is as fatal as it is cowardly to blink facts because they are not to our taste.",
            "A foolish consistency is the hobgoblin of small minds.",
            "Absence of evidence is not evidence of absence.",
            "You can recognize a pioneer by the arrows in his back.",
            "If the man doesn’t believe as we do, we say he is a crank, and that settles it. I mean, it does nowadays, because now we can’t burn him.",
            "Scientists are not the paragons of rationality, objectivity, openmindedness and humility that many of them might like others to believe.",
            "The common idea that scientists reject a theory as soon as it leads to a contradiction is just not so. When they get something that works at all they plunge ahead with it and ignore its weak spots… scientists are just as bad as the rest of the public in following fads and being influenced by mass enthusiasm.",
            "Once a new paradigm takes hold, its acceptance is extraordinarily rapid and one finds few who claim to have adhered to a discarded method.",
            "For every expert, there is an equal and opposite expert.",
            "One could not be a successful scientist without realizing that, in contrast to the popular conception supported by newspapers and mothers of scientists, a goodly number of scientists are not only narrow-minded and dull, but also just stupid.",
            "Desire for approval and recognition is a healthy motive, but the desire to be acknowledged as better, stronger, or more intelligent than a fellow being or fellow scholar easily leads to an excessively egoistic psychological adjustment, which may become injurious for the individual and for the community.",
            "A man with a new idea is a crank until he succeeds.",
            "Don’t worry about people stealing your ideas. If your ideas are that good, you’ll have to ram them down people’s throats.",
            "Physical concepts are the free creations of the human mind and are not, however it may seem, uniquely determined by the external world.",
        ],
        'User6' => [
            "I can’t see any farther. Giants are standing on my shoulders!",
            "In science it often happens that scientists say, 'You know that’s a really good argument; my position is mistaken' and then they would actually change their minds and you never hear that old view from them again. They really do it. It doesn’t happen as often as it should, because scientists are human and change is sometimes painful. But it happens every day. I cannot recall the last time something like that happened in politics or religion.",
            "When I examined myself and my methods of thought, I came to the conclusion that the gift of fantasy has meant more to me than my talent for absorbing positive knowledge.",
            "All truths are easy to understand once they are discovered; the point is to discover them.",
            "Advances are made by answering questions. Discoveries are made by questioning answers.",
            "The most erroneous stories are those we think we know best—and therefore never scrutinize or question.",
            "It is a good morning exercise for a research scientist to discard a pet hypothesis every day before breakfast. It keeps him young.",
            "Inquiry is fatal to certainty.",
            "In every work of genius we recognize our own rejected thoughts.",
            "There is no better soporific and sedative than skepticism.",
            "A new idea is delicate. It can be killed by a sneer or a yawn; it can be stabbed to death by a joke, or worried to death by a frown on the right person’s brow.",
            "A great many people think they are thinking when they are merely rearranging their prejudices.",
            "If you make people think they’re thinking, they’ll love you; but if you really make them think they’ll hate you.",
            "We must care to think about the unthinkable things, because when things become unthinkable, thinking stops and action becomes mindless.",
            "Wisest is she who knows she does not know.",
            "The only means of strengthening one’s intellect is to make up one’s mind about nothing—to let the mind be a thoroughfare for all thoughts. Not a select party.",
            "There is nothing so absurd that it cannot be believed as truth if repeated often enough.",
            "A lie repeated often enough becomes the truth.",
            "Never attribute to conspiracy that which is adequately explained by stupidity.",
            "Without deviation from the norm, progress is not possible.",
            "A witty saying proves nothing."
        ],
        'User7' => [
            "...By far the most usual way of handling phenomena so novel that they would make for a serious rearrangement of our preconceptions is to ignore them altogether, or to abuse those who bear witness for them.",
            "The pressure for conformity is enormous. I have experienced it in editors rejection of submitted papers, based on venomous criticism of anonymous referees. The replacement of impartial reviewing by censorship will be the death of science.",
            "When adults first become conscious of something new, they usually either attack or try to escape from it… Attack includes such mild forms as ridicule, and escape includes merely putting out of mind.",
            "All truth passes through three stages: First, it is ridiculed; Second, it is violently opposed; and Third, it is accepted as self-evident.",
            "Theories have four stages of acceptance: i) this is worthless nonsense; ii) this is an interesting, but perverse, point of view; iii) this is true, but quite unimportant; iv) I always said so",
            "When a thing is new, people say: ‘It is not true.’ Later, when its truth becomes obvious, they say: ‘It is not important.’ Finally, when its importance cannot be denied, they say: ‘Anyway, it is not new.’",
            "The radical invents the views. When he has worn them out the conservative adopts them.” ",
            "The soft-minded man always fears change. He feels security in the status quo, and he has an almost morbid fear of the new. For him, the greatest pain is the pain of a new idea.",
            "Loyalty to a petrified opinion never yet broke a chain or freed a human soul.",
            "No Pessimist ever discovered the secrets of the stars, or sailed to an uncharted land, or opened a new heaven to the human spirit",
        ]
    ];

    /**
     * @inheritDoc
     */
    public function searchTweetsByTwitterName(
        TwitterName $twitterName,
        TweetLimit $tweetLimit
    ) {
        if (!array_key_exists((string) $twitterName, self::TWEETS)) {
            throw new \InvalidArgumentException("Sorry $twitterName does not have any tweets yet.");
        }

        $randomEntries = array_rand(self::TWEETS[(string) $twitterName], $tweetLimit->getLimit());

        foreach (is_array($randomEntries) ? $randomEntries : [$randomEntries] as $randomEntry) {
            yield new Tweet(self::TWEETS[(string) $twitterName][$randomEntry]);
        }
    }
}
